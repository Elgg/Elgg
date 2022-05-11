<?php
/**
 *
 *  The main class for compiling.
 *
 */
namespace CssCrush;

class Process
{
    use EventEmitter;

    public function __construct($user_options = [], $context = [])
    {
        $config = Crush::$config;

        Crush::loadAssets();

        // Initialize properties.
        $this->cacheData = [];
        $this->mixins = [];
        $this->fragments = [];
        $this->references = [];
        $this->absoluteImports = [];
        $this->charset = null;
        $this->sources = [];
        $this->vars = [];
        $this->plugins = [];
        $this->misc = new \stdClass();
        $this->input = new \stdClass();
        $this->output = new \stdClass();
        $this->tokens = new Tokens();
        $this->functions = new Functions();
        $this->sourceMap = null;
        $this->selectorAliases = [];
        $this->selectorAliasesPatt = null;
        $this->io = new Crush::$config->io($this);

        $this->errors = [];
        $this->warnings = [];
        $this->debugLog = [];
        $this->stat = [];

        // Copy config values.
        $this->aliases = $config->aliases;

        // Options.
        $this->options = new Options($user_options, $config->options);

        // Context options.
        $context += ['type' => 'filter', 'data' => ''];
        $this->ioContext = $context['type'];

        // Keep track of global vars to maintain cache integrity.
        $this->options->global_vars = $config->vars;

        // Shortcut commonly used options to avoid __get() overhead.
        $this->docRoot = isset($this->options->doc_root) ? $this->options->doc_root : $config->docRoot;
        $this->generateMap = $this->ioContext === 'file' && $this->options->__get('source_map');
        $this->ruleFormatter = $this->options->__get('formatter');
        $this->minifyOutput = $this->options->__get('minify');
        $this->newline = $this->options->__get('newlines');

        $useContextOption = ! empty($this->options->context)
            && (php_sapi_name() === 'cli' || $context['type'] === 'filter');

        if ($context['type'] === 'file') {
            $file = $context['data'];
            $this->input->raw = $file;
            if (! ($inputFile = Util::resolveUserPath($file, null, $this->docRoot))) {
                throw new \Exception('Input file \'' . basename($file) . '\' not found.');
            }
            $inputDir = $useContextOption
                ? $this->options->context
                : dirname($inputFile);
            $this->resolveContext($inputDir, $inputFile);
        }
        elseif ($context['type'] === 'filter') {
            if ($useContextOption) {
                $this->resolveContext($this->options->context);
            }
            else {
                $this->resolveContext();
            }
            $this->input->string = $context['data'];
        }
    }

    public function release()
    {
        unset(
            $this->tokens,
            $this->mixins,
            $this->references,
            $this->cacheData,
            $this->misc,
            $this->plugins,
            $this->aliases,
            $this->selectorAliases
        );
    }

    public function resolveContext($input_dir = null, $input_file = null)
    {
        if ($input_file) {
            $this->input->path = $input_file;
            $this->input->filename = basename($input_file);
            $this->input->mtime = filemtime($input_file);
        }
        else {
            $this->input->path = null;
            $this->input->filename = null;
        }

        $this->input->dir = $input_dir ?: $this->docRoot;
        $this->input->dirUrl = substr($this->input->dir, strlen($this->docRoot));
        $this->output->dir = $this->io->getOutputDir();
        $this->output->filename = $this->io->getOutputFileName();
        $this->output->dirUrl = substr($this->output->dir, strlen($this->docRoot));

        $context_resolved = true;
        if ($input_file) {
            $output_dir = $this->output->dir;

            if (! file_exists($output_dir)) {
                warning("Output directory '$output_dir' doesn't exist.");
                $context_resolved = false;
            }
            elseif (! is_writable($output_dir)) {

                debug('Attempting to change permissions.');

                if (! @chmod($output_dir, 0755)) {
                    warning("Output directory '$output_dir' is unwritable.");
                    $context_resolved = false;
                }
                else {
                    debug('Permissions updated.');
                }
            }
        }

        $this->io->init();

        return $context_resolved;
    }


    #############################
    #  Boilerplate.

    protected function getBoilerplate()
    {
        $file = false;
        $boilerplateOption = $this->options->boilerplate;

        if ($boilerplateOption === true) {
            $file = Crush::$dir . '/boilerplate.txt';
        }
        elseif (is_string($boilerplateOption)) {
            if (file_exists($boilerplateOption)) {
                $file = $boilerplateOption;
            }
        }

        // Return an empty string if no file is found.
        if (! $file) {
            return '';
        }

        $boilerplate = file_get_contents($file);

        // Substitute any tags
        if (preg_match_all('~\{\{([^}]+)\}\}~', $boilerplate, $boilerplateMatches)) {

            // Command line arguments (if any).
            $commandArgs = 'n/a';
            if (isset($_SERVER['argv'])) {
                $argv = $_SERVER['argv'];
                array_shift($argv);
                $commandArgs = 'csscrush ' . implode(' ', $argv);
            }

            $tags = [
                'datetime' => @date('Y-m-d H:i:s O'),
                'year' => @date('Y'),
                'command' => $commandArgs,
                'plugins' => implode(',', $this->plugins),
                'version' => function ()  {
                    return Version::detect();
                },
                'compile_time' => function () {
                    $now = microtime(true) - Crush::$process->stat['compile_start_time'];
                    return round($now, 4) . ' seconds';
                },
            ];

            foreach (array_keys($boilerplateMatches[0]) as $index) {
                $tagName = trim($boilerplateMatches[1][$index]);
                $replacement = '?';
                if (isset($tags[$tagName])) {
                    $replacement =  is_callable($tags[$tagName]) ? $tags[$tagName]() : $tags[$tagName];
                }
                $replacements[] = $replacement;
            }
            $boilerplate = str_replace($boilerplateMatches[0], $replacements, $boilerplate);
        }

        // Pretty print.
        $EOL = $this->newline;
        $boilerplate = preg_split('~[\t]*'. Regex::$classes->newline . '[\t]*~', trim($boilerplate));
        $boilerplate = array_map('trim', $boilerplate);
        $boilerplate = "$EOL * " . implode("$EOL * ", $boilerplate);

        return "/*$boilerplate$EOL */$EOL";
    }


    #############################
    #  Selector aliases.

    protected function resolveSelectorAliases()
    {
        $this->string->pregReplaceCallback(
            Regex::make('~@selector(?:-(?<type>alias|splat))? +\:?(?<name>{{ident}}) +(?<handler>[^;]+) *;~iS'),
            function ($m) {
                $name = strtolower($m['name']);
                $type = ! empty($m['type']) ? strtolower($m['type']) : 'alias';
                $handler = Util::stripCommentTokens($m['handler']);
                Crush::$process->selectorAliases[$name] = new SelectorAlias($handler, $type);
            });

        // Create the selector aliases pattern and store it.
        if ($this->selectorAliases) {
            $names = implode('|', array_keys($this->selectorAliases));
            $this->selectorAliasesPatt
                = Regex::make('~\:(' . $names . '){{RB}}(\()?~iS');
        }
    }

    public function addSelectorAlias($name, $handler, $type = 'alias')
    {
        if ($type != 'callback') {
            $handler = $this->tokens->capture($handler, 's');
        }
        $this->selectorAliases[$name] = new SelectorAlias($handler, $type);
    }


    #############################
    #  Aliases.

    protected function filterAliases()
    {
        // If a vendor target is given, we prune the aliases array.
        $vendors = $this->options->vendor_target;

        // Default vendor argument, so use all aliases as normal.
        if ('all' === $vendors) {

            return;
        }

        // For expicit 'none' argument turn off aliases.
        if ('none' === $vendors) {
            $this->aliases = Crush::$config->bareAliases;

            return;
        }

        // Normalize vendor names and create regex patt.
        $vendor_names = (array) $vendors;
        foreach ($vendor_names as &$vendor_name) {
            $vendor_name = trim($vendor_name, '-');
        }
        $vendor_patt = '~^\-(' . implode('|', $vendor_names) . ')\-~i';


        // Loop the aliases array, filter down to the target vendor.
        foreach ($this->aliases as $section => $group_array) {

            // Declarations aliases.
            if ($section === 'declarations') {

                foreach ($group_array as $property => $values) {
                    foreach ($values as $value => $prefix_values) {
                        foreach ($prefix_values as $index => $declaration) {

                            if (in_array($declaration[2], $vendor_names)) {
                                continue;
                            }

                            // Unset uneeded aliases.
                            unset($this->aliases[$section][$property][$value][$index]);

                            if (empty($this->aliases[$section][$property][$value])) {
                                unset($this->aliases[$section][$property][$value]);
                            }
                            if (empty($this->aliases[$section][$property])) {
                                unset($this->aliases[$section][$property]);
                            }
                        }
                    }
                }
            }

            // Function group aliases.
            elseif ($section === 'function_groups') {

                foreach ($group_array as $func_group => $vendors) {
                    foreach (array_keys($vendors) as $vendor) {
                        if (! in_array($vendor, $vendor_names)) {
                            unset($this->aliases['function_groups'][$func_group][$vendor]);
                        }
                    }
                }
            }

            // Everything else.
            else {
                foreach ($group_array as $alias_keyword => $prefix_array) {

                    // Skip over pointers to function groups.
                    if ($prefix_array[0] === '.') {
                        continue;
                    }

                    $result = [];

                    foreach ($prefix_array as $prefix) {
                        if (preg_match($vendor_patt, $prefix)) {
                            $result[] = $prefix;
                        }
                    }

                    // Prune the whole alias keyword if there is no result.
                    if (empty($result)) {
                        unset($this->aliases[$section][$alias_keyword]);
                    }
                    else {
                        $this->aliases[$section][$alias_keyword] = $result;
                    }
                }
            }
        }
    }


    #############################
    #  Plugins.

    protected function filterPlugins()
    {
        $this->plugins = array_unique($this->options->plugins);

        foreach ($this->plugins as $plugin) {
            Crush::enablePlugin($plugin);
        }
    }


    #############################
    #  Variables.

    protected function captureVars()
    {
        Crush::$process->vars = Crush::$process->string->captureDirectives(['set', 'define'], [
            'singles' => true,
            'lowercase_keys' => false,
        ]) + Crush::$process->vars;

        // For convenience adding a runtime variable for cache busting linked resources.
        $this->vars['timestamp'] = (int) $this->stat['compile_start_time'];

        // In-file variables override global variables.
        $this->vars += Crush::$config->vars;

        // Runtime variables override in-file variables.
        if (! empty($this->options->vars)) {
            $this->vars = $this->options->vars + $this->vars;
        }

        // Place variables referenced inside variables.
        foreach ($this->vars as &$value) {
            $this->placeVars($value);
        }
    }

    protected function placeAllVars()
    {
        $this->placeVars($this->string->raw);

        $rawTokens =& $this->tokens->store;

        // Repeat above steps for variables embedded in string tokens.
        foreach ($rawTokens->s as $label => &$value) {
            $this->placeVars($value);
        }

        // Repeat above steps for variables embedded in URL tokens.
        foreach ($rawTokens->u as $label => $url) {
            if (! $url->isData && $this->placeVars($url->value)) {
                // Re-evaluate $url->value if anything has been interpolated.
                $url->evaluate();
            }
        }
    }

    protected function placeVars(&$value)
    {
        static $varFunction, $varFunctionSimple;
        if (! $varFunction) {
            $varFunctionSimple = Regex::make('~\$\( \s* ({{ ident }}) \s* \)~xS');
            $varFunction = new Functions(['$' => function ($rawArgs) {
                $args = Functions::parseArgsSimple($rawArgs);
                if (isset(Crush::$process->vars[$args[0]])) {
                    return Crush::$process->vars[$args[0]];
                }
                else {
                    return isset($args[1]) ? $args[1] : '';
                }
            }]);
        }

        // Variables with no default value.
        $value = preg_replace_callback($varFunctionSimple, function ($m) {
            $varName = $m[1];
            if (isset(Crush::$process->vars[$varName])) {
                return Crush::$process->vars[$varName];
            }
        }, $value, -1, $varsPlaced);

        // Variables with default value.
        if (strpos($value, '$(') !== false) {

            // Assume at least one replace.
            $varsPlaced = true;

            // Variables may be nested so need to apply full function parsing.
            $value = $varFunction->apply($value);
        }

        // If we know replacements have been made we may want to update $value. e.g URL tokens.
        return $varsPlaced;
    }

    #############################
    #  @for..in blocks.

    protected function resolveLoops()
    {
        $LOOP_VAR_PATT = '~\#\( \s* (?<arg>[a-zA-Z][\.a-zA-Z0-9-_]*) \s* \)~x';
        $LOOP_PATT = Regex::make('~
            (?<expression>
                @for \s+ (?<var>{{ident}}) \s+ in \s+ (?<list>[^{]+)
            ) \s*
            {{ block }}
        ~xiS');

        $apply_scope = function ($str, $context) use ($LOOP_VAR_PATT, $LOOP_PATT) {
            // Need to temporarily hide child block scopes.
            $child_scopes = [];
            $str = preg_replace_callback($LOOP_PATT, function ($m) use (&$child_scopes) {
                $label = '?B' . count($child_scopes) . '?';
                $child_scopes[$label] = $m['block'];
                return $m['expression'] . $label;
            }, $str);

            $str = preg_replace_callback($LOOP_VAR_PATT, function ($m) use ($context) {
                // Normalize casing of built-in loop variables.
                // User variables are case-sensitive.
                $arg = preg_replace_callback('~^loop\.(parent\.)?counter0?$~i', function ($m) {
                    return strtolower($m[0]);
                }, $m['arg']);

                return isset($context[$arg]) ? $context[$arg] : '';
            }, $str);

            return str_replace(array_keys($child_scopes), array_values($child_scopes), $str);
        };

        $resolve_list = function ($list) {
            // Resolve the list of items for iteration.
            // Either a generator function or a plain list.
            $items = [];
            $this->placeVars($list);
            $list = $this->functions->apply($list);
            if (preg_match(Regex::make('~(?<func>range){{ parens }}~ix'), $list, $m)) {
                $func = strtolower($m['func']);
                $args = Functions::parseArgs($m['parens_content']);
                switch ($func) {
                    case 'range':
                        $items = range(...$args);
                        break;
                }
            }
            else {
                $items = Util::splitDelimList($list);
            }

            return $items;
        };

        $unroll = function ($str, $context = []) use (&$unroll, $LOOP_PATT, $apply_scope, $resolve_list) {
            $str = $apply_scope($str, $context);
            while (preg_match($LOOP_PATT, $str, $m, PREG_OFFSET_CAPTURE)) {
                $str = substr_replace($str, '', $m[0][1], strlen($m[0][0]));
                $context['loop.parent.counter'] = isset($context['loop.counter']) ? $context['loop.counter'] : -1;
                $context['loop.parent.counter0'] = isset($context['loop.counter0']) ? $context['loop.counter0'] : -1;
                foreach ($resolve_list($m['list'][0]) as $index => $value) {
                    $str .= $unroll($m['block_content'][0], [
                        $m['var'][0] => $value,
                        'loop.counter' => $index + 1,
                        'loop.counter0' => $index,
                    ] + $context);
                }
            }

            return $str;
        };

        $this->string->pregReplaceCallback($LOOP_PATT, function ($m) use ($unroll) {
            return Template::tokenize($unroll(Template::unTokenize($m[0])));
        });
    }

    #############################
    #  @ifdefine blocks.

    protected function resolveIfDefines()
    {
        $ifdefinePatt = Regex::make('~@if(?:set|define) \s+ (?<negate>not \s+)? (?<name>{{ ident }}) \s* {{ parens }}? \s* \{~ixS');

        $matches = $this->string->matchAll($ifdefinePatt);

        while ($match = array_pop($matches)) {

            $curlyMatch = new BalancedMatch($this->string, $match[0][1]);

            if (! $curlyMatch->match) {
                continue;
            }

            $negate = $match['negate'][1] != -1;
            $nameDefined = isset($this->vars[$match['name'][0]]);

            $valueDefined = isset($match['parens_content'][0]);
            $valueMatch = false;
            if ($nameDefined && $valueDefined) {
                $testValue = Util::rawValue(trim($match['parens_content'][0]));
                $varValue = Util::rawValue($this->vars[$match['name'][0]]);
                $valueMatch = $varValue == $testValue;
            }

            if (
                ( $valueDefined && !$negate && $valueMatch )
                || ( $valueDefined && $negate && !$valueMatch )
                || ( !$valueDefined && !$negate && $nameDefined )
                || ( !$valueDefined && $negate && !$nameDefined )
            ) {
                $curlyMatch->unWrap();
            }
            else {
                $curlyMatch->replace('');
            }
        }
    }


    #############################
    #  Mixins.

    protected function captureMixins()
    {
        $this->string->pregReplaceCallback(Regex::$patt->mixin, function ($m) {
            Crush::$process->mixins[$m['name']] = new Mixin($m['block_content']);
        });
    }


    #############################
    #  Fragments.

    protected function resolveFragments()
    {
        $fragments =& Crush::$process->fragments;

        $this->string->pregReplaceCallback(Regex::$patt->fragmentCapture, function ($m) use (&$fragments) {
            $fragments[$m['name']] = new Fragment(
                    $m['block_content'],
                    ['name' => strtolower($m['name'])]
                );
            return '';
        });

        $this->string->pregReplaceCallback(Regex::$patt->fragmentInvoke, function ($m) use (&$fragments) {
            $fragment = isset($fragments[$m['name']]) ? $fragments[$m['name']] : null;
            if ($fragment) {
                $args = [];
                if (isset($m['parens'])) {
                    $args = Functions::parseArgs($m['parens_content']);
                }
                return $fragment($args);
            }
            return '';
        });
    }


    #############################
    #  Rules.

    public function captureRules()
    {
        $tokens = $this->tokens;

        $rulePatt = Regex::make('~
            (?<trace_token> {{ t_token }})
            \s*
            (?<selector> [^{]+)
            \s*
            {{ block }}
        ~xiS');
        $rulesAndMediaPatt = Regex::make('~{{ r_token }}|@media[^\{]+{{ block }}~iS');

        $count = preg_match_all(Regex::$patt->t_token, $this->string->raw, $traceMatches, PREG_OFFSET_CAPTURE);
        while ($count--) {

            $traceOffset = $traceMatches[0][$count][1];

            preg_match($rulePatt, $this->string->raw, $ruleMatch, PREG_UNMATCHED_AS_NULL, $traceOffset);

            $selector = trim($ruleMatch['selector']);
            $block = trim($ruleMatch['block_content']);
            $replace = '';

            // If rules are nested inside we set their parent property.
            if (preg_match_all(Regex::$patt->r_token, $block, $childMatches)) {

                $block = preg_replace_callback($rulesAndMediaPatt, function ($m) use (&$replace) {
                    $replace .= $m[0];
                    return '';
                }, $block);

                $rule = new Rule($selector, $block, $ruleMatch['trace_token']);
                foreach ($childMatches[0] as $childToken) {
                    $childRule = $tokens->get($childToken);
                    if (! $childRule->parent) {
                        $childRule->parent = $rule;
                    }
                }
            }
            else  {
                $rule = new Rule($selector, $block, $ruleMatch['trace_token']);
            }

            $replace = $tokens->add($rule, 'r', $rule->label) . $replace;

            $this->string->splice($replace, $traceOffset, strlen($ruleMatch[0]));
        }

        // Flip, since we just captured rules in reverse order.
        $tokens->store->r = array_reverse($tokens->store->r);

        foreach ($tokens->store->r as $rule) {
            if ($rule->parent) {
                $rule->selectors->merge(array_keys($rule->parent->selectors->store));
            }
        }

        // Cleanup unusable rules.
        $this->string->pregReplaceCallback(Regex::$patt->r_token, function ($m) use ($tokens) {
            $ruleToken = $m[0];
            $rule = $tokens->store->r[$ruleToken];
            if (empty($rule->declarations->store) && ! $rule->extendArgs) {
                unset($tokens->store->r[$ruleToken]);
                return '';
            }
            return $ruleToken;
        });
    }

    protected function processRules()
    {
        // Create table of name/selector to rule references.
        $namedReferences = [];

        $previousRule = null;
        foreach ($this->tokens->store->r as $rule) {
            if ($rule->name) {
                $namedReferences[$rule->name] = $rule;
            }
            foreach ($rule->selectors as $selector) {
                $this->references[$selector->readableValue] = $rule;
            }
            if ($previousRule) {
                $rule->previous = $previousRule;
                $previousRule->next = $rule;
            }
            $previousRule = $rule;
        }

        // Explicit named references take precedence.
        $this->references = $namedReferences + $this->references;

        foreach ($this->tokens->store->r as $rule) {

            $rule->declarations->flatten();
            $rule->declarations->process();

            $this->emit('rule_prealias', $rule);

            $rule->declarations->aliasProperties($rule->vendorContext);
            $rule->declarations->aliasFunctions($rule->vendorContext);
            $rule->declarations->aliasDeclarations($rule->vendorContext);

            $this->emit('rule_postalias', $rule);

            $rule->selectors->expand();
            $rule->applyExtendables();

            $this->emit('rule_postprocess', $rule);
        }
    }


    #############################
    #  @-rule aliasing.

    protected function aliasAtRules()
    {
        if (empty($this->aliases['at-rules'])) {

            return;
        }

        $aliases = $this->aliases['at-rules'];
        $regex = Regex::$patt;

        foreach ($aliases as $at_rule => $at_rule_aliases) {

            $matches = $this->string->matchAll("~@$at_rule" . '[\s{]~i');

            // Find at-rules that we want to alias.
            while ($match = array_pop($matches)) {

                $curly_match = new BalancedMatch($this->string, $match[0][1]);

                if (! $curly_match->match) {
                    // Couldn't match the block.
                    continue;
                }

                // Build up string with aliased blocks for splicing.
                $original_block = $curly_match->whole();
                $new_blocks = [];

                foreach ($at_rule_aliases as $alias) {

                    // Copy original block, replacing at-rule with alias name.
                    $copy_block = str_replace("@$at_rule", "@$alias", $original_block);

                    // Aliases are nearly always prefixed, capture the current vendor name.
                    preg_match($regex->vendorPrefix, $alias, $vendor);

                    $vendor = $vendor ? $vendor[1] : null;

                    // Duplicate rules.
                    if (preg_match_all($regex->r_token, $copy_block, $copy_matches)) {

                        $originals = [];
                        $replacements = [];

                        foreach ($copy_matches[0] as $rule_label) {

                            // Clone the matched rule.
                            $originals[] = $rule_label;
                            $clone_rule = clone $this->tokens->get($rule_label);

                            $clone_rule->vendorContext = $vendor;

                            // Store the clone.
                            $replacements[] = $this->tokens->add($clone_rule);
                        }

                        // Finally replace the original labels with the cloned rule labels.
                        $copy_block = str_replace($originals, $replacements, $copy_block);
                    }

                    // Add the copied block to the stack.
                    $new_blocks[] = $copy_block;
                }

                // The original version is always pushed last in the list.
                $new_blocks[] = $original_block;

                // Splice in the blocks.
                $curly_match->replace(implode("\n", $new_blocks));
            }
        }
    }


    #############################
    #  Compile / collate.

    protected function collate()
    {
        $options = $this->options;
        $minify = $options->minify;
        $EOL = $this->newline;

        // Formatting replacements.
        // Strip newlines added during processing.
        $regex_replacements = [];
        $regex_replacements['~\n+~'] = '';

        if ($minify) {
            // Strip whitespace around colons used in @-rule arguments.
            $regex_replacements['~ ?\: ?~'] = ':';
        }
        else {
            // Pretty printing.
            $regex_replacements['~}~'] = "$0$EOL$EOL";
            $regex_replacements['~([^\s])\{~'] = "$1 {";
            $regex_replacements['~ ?(@[^{]+\{)~'] = "$1$EOL";
            $regex_replacements['~ ?(@[^;]+\;)~'] = "$1$EOL";

            // Trim leading spaces on @-rules and some tokens.
            $regex_replacements[Regex::make('~ +([@}]|\?[rc]{{token_id}}\?)~S')] = "$1";

            // Additional newline between adjacent rules and comments.
            $regex_replacements[Regex::make('~({{r_token}}) (\s*) ({{c_token}})~xS')] = "$1$EOL$2$3";
        }

        // Apply all formatting replacements.
        $this->string->pregReplaceHash($regex_replacements)->lTrim();

        $this->string->restore('r');

        // Record stats then drop rule objects to reclaim memory.
        Crush::runStat('selector_count', 'rule_count', 'vars');
        $this->tokens->store->r = [];

        // If specified, apply advanced minification.
        if (is_array($minify)) {
            if (in_array('colors', $minify)) {
                $this->minifyColors();
            }
        }

        $this->decruft();

        if (! $minify) {
            // Add newlines after comments.
            foreach ($this->tokens->store->c as $token => &$comment) {
                $comment .= $EOL;
            }

            // Insert comments and do final whitespace cleanup.
            $this->string
                ->restore('c')
                ->trim()
                ->append($EOL);
        }

        // Insert URLs.
        $urls = $this->tokens->store->u;
        if ($urls) {

            $link = Util::getLinkBetweenPaths($this->output->dir, $this->input->dir);
            $make_urls_absolute = $options->rewrite_import_urls === 'absolute';

            foreach ($urls as $token => $url) {

                if ($url->isRelative && ! $url->noRewrite) {
                    if ($make_urls_absolute) {
                        $url->toRoot();
                    }
                    // If output dir is different to input dir prepend a link between the two.
                    elseif ($link && $options->rewrite_import_urls) {
                        $url->prepend($link);
                    }
                }
            }
        }

        if ($this->absoluteImports) {
            $absoluteImports = '';
            $closing = $minify ? ';' : ";$EOL";
            foreach ($this->absoluteImports as $import) {
                $absoluteImports .= "@import $import->url" . ($import->media ? " $import->media" : '') . $closing;
            }
            $this->string->prepend($absoluteImports);
        }

        if ($options->boilerplate) {
            $this->string->prepend($this->getBoilerplate());
        }

        if ($this->charset) {
            $this->string->prepend("@charset \"$this->charset\";$EOL");
        }

        $this->string->restore(['u', 's']);

        if ($this->generateMap) {
            $this->generateSourceMap();
        }
    }

    private $iniOriginal = [];
    public function preCompile()
    {
        foreach ([
            'pcre.backtrack_limit' => 1000000,
            'pcre.jit' => 0, // Have run into PREG_JIT_STACKLIMIT_ERROR (issue #82).
            'memory_limit' => '128M',
        ] as $name => $value) {
            $this->iniOriginal[$name] = ini_get($name);
            if ($name === 'memory_limit' && $this->returnBytes(ini_get($name)) > $this->returnBytes($value)) {
                continue;
            }
            ini_set($name, $value);
        }

        $this->filterPlugins();
        $this->filterAliases();

        $this->functions->setPattern(true);

        $this->stat['compile_start_time'] = microtime(true);
    }

    private function returnBytes(string $value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (float) $value;

        switch ($last) {
            // The 'G' modifier is available
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    public function postCompile()
    {
        $this->release();

        Crush::runStat('compile_time');

        foreach ($this->iniOriginal as $name => $value) {
            ini_set($name, $value);
        }
    }

    public function compile()
    {
        $this->preCompile();

        $importer = new Importer($this);
        $this->string = new StringObject($importer->collate());

        // Capture phase 0 hook: Before all variables have resolved.
        $this->emit('capture_phase0', $this);

        $this->captureVars();

        $this->resolveIfDefines();

        $this->resolveLoops();

        $this->placeAllVars();

        // Capture phase 1 hook: After all variables have resolved.
        $this->emit('capture_phase1', $this);

        $this->resolveSelectorAliases();

        $this->captureMixins();

        $this->resolveFragments();

        // Capture phase 2 hook: After most built-in directives have resolved.
        $this->emit('capture_phase2', $this);

        $this->captureRules();

        // Calling functions on media query lists.
        $process = $this;
        $this->string->pregReplaceCallback('~@media\s+(?<media_list>[^{]+)\{~i', function ($m) use (&$process) {
            return "@media {$process->functions->apply($m['media_list'])}{";
        });

        $this->aliasAtRules();

        $this->processRules();

        $this->collate();

        $this->postCompile();

        return $this->string;
    }


    #############################
    #  Source maps.

    public function generateSourceMap()
    {
        $this->sourceMap = [
            'version' => 3,
            'file' => $this->output->filename,
            'sources' => [],
        ];
        foreach ($this->sources as $source) {
            $this->sourceMap['sources'][] = Util::getLinkBetweenPaths($this->output->dir, $source, false);
        }

        $token_patt = Regex::make('~\?[tm]{{token_id}}\?~S');
        $mappings = [];
        $lines = preg_split(Regex::$patt->newline, $this->string->raw);
        $tokens =& $this->tokens->store;

        // All mappings are calculated as delta values.
        $previous_dest_col = 0;
        $previous_src_file = 0;
        $previous_src_line = 0;
        $previous_src_col = 0;

        foreach ($lines as &$line_text) {

            $line_segments = [];

            while (preg_match($token_patt, $line_text, $m, PREG_OFFSET_CAPTURE)) {

                list($token, $dest_col) = $m[0];
                $token_type = $token[1];

                if (isset($tokens->{$token_type}[$token])) {

                    list($src_file, $src_line, $src_col) = explode(',', $tokens->{$token_type}[$token]);
                    $line_segments[] =
                        Util::vlqEncode($dest_col - $previous_dest_col) .
                        Util::vlqEncode($src_file - $previous_src_file) .
                        Util::vlqEncode($src_line - $previous_src_line) .
                        Util::vlqEncode($src_col - $previous_src_col);

                    $previous_dest_col = $dest_col;
                    $previous_src_file = $src_file;
                    $previous_src_line = $src_line;
                    $previous_src_col = $src_col;
                }
                $line_text = substr_replace($line_text, '', $dest_col, strlen($token));
            }

            $mappings[] = implode(',', $line_segments);
        }

        $this->string->raw = implode($this->newline, $lines);
        $this->sourceMap['mappings'] = implode(';', $mappings);
    }


    #############################
    #  Decruft.

    protected function decruft()
    {
        return $this->string->pregReplaceHash([

            // Strip leading zeros on floats.
            '~([: \(,])(-?)0(\.\d+)~S' => '$1$2$3',

            // Strip unnecessary units on zero values for length types.
            '~([: \(,])\.?0' . Regex::$classes->length_unit . '~iS' => '${1}0',

            // Collapse zero lists.
            '~(\: *)(?:0 0 0|0 0 0 0) *([;}])~S' => '${1}0$2',

            // Collapse zero lists 2nd pass.
            '~(padding|margin|border-radius) ?(\: *)0 0 *([;}])~iS' => '${1}${2}0$3',

            // Dropping redundant trailing zeros on TRBL lists.
            '~(\: *)(-?(?:\d+)?\.?\d+[a-z]{1,4}) 0 0 0 *([;}])~iS' => '$1$2 0 0$3',
            '~(\: *)0 0 (-?(?:\d+)?\.?\d+[a-z]{1,4}) 0 *([;}])~iS' => '${1}0 0 $2$3',

            // Compress hex codes.
            Regex::$patt->cruftyHex => '#$1$2$3',
        ]);
    }


    #############################
    #  Advanced minification.

    protected function minifyColors()
    {
        static $keywords_patt, $functions_patt;

        $minified_keywords = Color::getMinifyableKeywords();

        if (! $keywords_patt) {
            $keywords_patt = '~(?<![\w\.#-])(' . implode('|', array_keys($minified_keywords)) . ')(?![\w\.#\]-])~iS';
            $functions_patt = Regex::make('~{{ LB }}(rgb|hsl)\(([^\)]{5,})\)~iS');
        }

        $this->string->pregReplaceCallback($keywords_patt, function ($m) use ($minified_keywords) {
            return $minified_keywords[strtolower($m[0])];
        });

        $this->string->pregReplaceCallback($functions_patt, function ($m) {
            $args = Functions::parseArgs(trim($m[2]));
            if (stripos($m[1], 'hsl') === 0) {
                $args = Color::cssHslToRgb($args);
            }
            return Color::rgbToHex($args);
        });
    }
}
