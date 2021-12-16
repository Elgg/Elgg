<?php
/**
 *
 * Declaration lists.
 *
 */
namespace CssCrush;

class DeclarationList extends Iterator
{
    public $flattened = true;
    public $processed = false;
    protected $rule;

    public $properties = [];
    public $canonicalProperties = [];

    // Declarations hash table for inter-rule this() referencing.
    public $data = [];

    // Declarations hash table for external query() referencing.
    public $queryData = [];

    public function __construct($declarationsString, Rule $rule)
    {
        parent::__construct();

        $this->rule = $rule;
        $pairs = DeclarationList::parse($declarationsString);

        foreach ($pairs as $index => $pair) {

            list($prop, $value) = $pair;

            // Directives.
            if ($prop === 'extends') {
                $this->rule->addExtendSelectors($value);
                unset($pairs[$index]);
            }
            elseif ($prop === 'name') {
                if (! $this->rule->name) {
                    $this->rule->name = $value;
                }
                unset($pairs[$index]);
            }
        }

        // Build declaration list.
        foreach ($pairs as $index => &$pair) {

            list($prop, $value) = $pair;

            if (trim($value) !== '') {

                if ($prop === 'mixin') {
                    $this->flattened = false;
                    $this->store[] = $pair;
                }
                else {
                    // Only store to $this->data if the value does not itself make a
                    // this() call to avoid circular references.
                    if (! preg_match(Regex::$patt->thisFunction, $value)) {
                        $this->data[strtolower($prop)] = $value;
                    }
                    $this->add($prop, $value, $index);
                }
            }
        }
    }

    public function add($property, $value, $contextIndex = 0)
    {
        $declaration = new Declaration($property, $value, $contextIndex);

        if ($declaration->valid) {

            $this->index($declaration);
            $this->store[] = $declaration;
            return $declaration;
        }

        return false;
    }

    public function reset(array $declaration_stack)
    {
        $this->store = $declaration_stack;

        $this->updateIndex();
    }

    public function index($declaration)
    {
        $property = $declaration->property;

        if (isset($this->properties[$property])) {
            $this->properties[$property]++;
        }
        else {
            $this->properties[$property] = 1;
        }
        $this->canonicalProperties[$declaration->canonicalProperty] = true;
    }

    public function updateIndex()
    {
        $this->properties = [];
        $this->canonicalProperties = [];

        foreach ($this->store as $declaration) {
            $this->index($declaration);
        }
    }

    public function propertyCount($property)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : 0;
    }

    public function join($glue = ';')
    {
        return implode($glue, $this->store);
    }

    /*
        Aliasing.
    */
    public function aliasProperties($vendor_context = null)
    {
        $aliased_properties =& Crush::$process->aliases['properties'];

        // Bail early if nothing doing.
        if (! array_intersect_key($aliased_properties, $this->properties)) {
            return;
        }

        $stack = [];
        $rule_updated = false;
        $regex = Regex::$patt;

        foreach ($this->store as $declaration) {

            // Check declaration against vendor context.
            if ($vendor_context && $declaration->vendor && $declaration->vendor !== $vendor_context) {
                continue;
            }

            if ($declaration->skip) {
                $stack[] = $declaration;
                continue;
            }

            // Shim in aliased properties.
            if (isset($aliased_properties[$declaration->property])) {

                foreach ($aliased_properties[$declaration->property] as $prop_alias) {

                    // If an aliased version already exists do not create one.
                    if ($this->propertyCount($prop_alias)) {
                        continue;
                    }

                    // Get property alias vendor.
                    preg_match($regex->vendorPrefix, $prop_alias, $alias_vendor);

                    // Check against vendor context.
                    if ($vendor_context && $alias_vendor && $alias_vendor[1] !== $vendor_context) {
                        continue;
                    }

                    // Create the aliased declaration.
                    $copy = clone $declaration;
                    $copy->property = $prop_alias;

                    // Set the aliased declaration vendor property.
                    $copy->vendor = null;
                    if ($alias_vendor) {
                        $copy->vendor = $alias_vendor[1];
                    }

                    $stack[] = $copy;
                    $rule_updated = true;
                }
            }

            // Un-aliased property or a property alias that has been manually set.
            $stack[] = $declaration;
        }

        // Re-assign if any updates have been made.
        if ($rule_updated) {
            $this->reset($stack);
        }
    }

    public function aliasFunctions($vendor_context = null)
    {
        $function_aliases =& Crush::$process->aliases['functions'];
        $function_alias_groups =& Crush::$process->aliases['function_groups'];

        // The new modified set of declarations.
        $new_set = [];
        $rule_updated = false;

        // Shim in aliased functions.
        foreach ($this->store as $declaration) {

            // No functions, bail.
            if (! $declaration->functions || $declaration->skip) {
                $new_set[] = $declaration;
                continue;
            }

            // Get list of functions used in declaration that are alias-able, bail if none.
            $intersect = array_intersect_key($declaration->functions, $function_aliases);
            if (! $intersect) {
                $new_set[] = $declaration;
                continue;
            }

            // Keep record of which groups have been applied.
            $processed_groups = [];

            foreach (array_keys($intersect) as $fn_name) {

                // Store for all the duplicated declarations.
                $prefixed_copies = [];

                // Grouped function aliases.
                if ($function_aliases[$fn_name][0] === '.') {

                    $group_id = $function_aliases[$fn_name];

                    // If this group has been applied we can skip over.
                    if (isset($processed_groups[$group_id])) {
                        continue;
                    }

                    // Mark group as applied.
                    $processed_groups[$group_id] = true;

                    $groups =& $function_alias_groups[$group_id];

                    foreach ($groups as $group_key => $replacements) {

                        // If the declaration is vendor specific only create aliases for the same vendor.
                        if (
                            ($declaration->vendor && $group_key !== $declaration->vendor) ||
                            ($vendor_context && $group_key !== $vendor_context)
                        ) {
                            continue;
                        }

                        $copy = clone $declaration;

                        // Make swaps.
                        $copy->value = preg_replace(
                            $replacements['find'],
                            $replacements['replace'],
                            $copy->value
                        );
                        $prefixed_copies[] = $copy;
                        $rule_updated = true;
                    }

                    // Post fixes.
                    if (isset(PostAliasFix::$functions[$group_id])) {
                        call_user_func(PostAliasFix::$functions[$group_id], $prefixed_copies, $group_id);
                    }
                }

                // Single function aliases.
                else {
                    foreach ($function_aliases[$fn_name] as $fn_alias) {

                        // If the declaration is vendor specific only create aliases for the same vendor.
                        if ($declaration->vendor) {
                            preg_match(Regex::$patt->vendorPrefix, $fn_alias, $m);
                            if (
                                $m[1] !== $declaration->vendor ||
                                ($vendor_context && $m[1] !== $vendor_context)
                            ) {
                                continue;
                            }
                        }

                        $copy = clone $declaration;

                        // Make swaps.
                        $copy->value = preg_replace(
                            Regex::make("~{{ LB }}$fn_name(?=\()~iS"),
                            $fn_alias,
                            $copy->value
                        );
                        $prefixed_copies[] = $copy;
                        $rule_updated = true;
                    }

                    // Post fixes.
                    if (isset(PostAliasFix::$functions[$fn_name])) {
                        call_user_func(PostAliasFix::$functions[$fn_name], $prefixed_copies, $fn_name);
                    }
                }

                $new_set = array_merge($new_set, $prefixed_copies);
            }
            $new_set[] = $declaration;
        }

        // Re-assign if any updates have been made.
        if ($rule_updated) {
            $this->reset($new_set);
        }
    }

    public function aliasDeclarations($vendor_context = null)
    {
        $declaration_aliases =& Crush::$process->aliases['declarations'];

        // First test for the existence of any aliased properties.
        if (! ($intersect = array_intersect_key($declaration_aliases, $this->properties))) {
            return;
        }

        $intersect = array_flip(array_keys($intersect));
        $new_set = [];
        $rule_updated = false;

        foreach ($this->store as $declaration) {

            // Check the current declaration property is actually aliased.
            if (isset($intersect[$declaration->property]) && ! $declaration->skip) {

                // Iterate on the current declaration property for value matches.
                foreach ($declaration_aliases[$declaration->property] as $value_match => $replacements) {

                    // Create new alias declaration if the property and value match.
                    if ($declaration->value === $value_match) {

                        foreach ($replacements as $values) {

                            // Check the vendor against context.
                            if ($vendor_context && $vendor_context !== $values[2]) {
                                continue;
                            }

                            // If the replacement property is null use the original declaration property.
                            $new = new Declaration(
                                ! empty($values[0]) ? $values[0] : $declaration->property,
                                $values[1]
                                );
                            $new->important = $declaration->important;
                            $new_set[] = $new;
                            $rule_updated = true;
                        }
                    }
                }
            }
            $new_set[] = $declaration;
        }

        // Re-assign if any updates have been made.
        if ($rule_updated) {
            $this->reset($new_set);
        }
    }

    public static function parse($str, $options = [])
    {
        $str = Util::stripCommentTokens($str);
        $lines = preg_split('~\s*;\s*~', $str, 0, PREG_SPLIT_NO_EMPTY);

        $options += [
            'keyed' => false,
            'ignore_directives' => false,
            'lowercase_keys' => false,
            'context' => null,
            'flatten' => false,
            'apply_hooks' => false,
        ];

        $pairs = [];

        foreach ($lines as $line) {

            if (! $options['ignore_directives'] && preg_match(Regex::$patt->ruleDirective, $line, $m)) {

                if (! empty($m[1])) {
                    $property = 'mixin';
                }
                elseif (! empty($m[2])) {
                    $property = 'extends';
                }
                else {
                    $property = 'name';
                }
                $value = trim(substr($line, strlen($m[0])));
            }
            elseif (($colon_pos = strpos($line, ':')) !== false) {

                $property = trim(substr($line, 0, $colon_pos));
                $value = trim(substr($line, $colon_pos + 1));

                if ($options['lowercase_keys']) {
                    $property = strtolower($property);
                }

                if ($options['apply_hooks']) {
                    Crush::$process->emit('declaration_preprocess', [
                        'property' => &$property,
                        'value' => &$value,
                    ]);
                }
            }
            else {
                continue;
            }

            if ($property === '' || $value === '') {
                continue;
            }

            if ($property === 'mixin' && $options['flatten']) {
                $pairs = Mixin::merge($pairs, $value, [
                    'keyed' => $options['keyed'],
                    'context' => $options['context'],
                ]);
            }
            elseif ($options['keyed']) {
                $pairs[$property] = $value;
            }
            else {
                $pairs[] = [$property, $value];
            }
        }

        return $pairs;
    }

    public function flatten()
    {
        if ($this->flattened) {
            return;
        }

        $newSet = [];
        foreach ($this->store as $declaration) {
            if (is_array($declaration) && $declaration[0] === 'mixin') {
                foreach (Mixin::merge([], $declaration[1], ['context' => $this->rule]) as $mixable) {
                    if ($mixable instanceof Declaration) {
                        $clone = clone $mixable;
                        $clone->index = count($newSet);
                        $newSet[] = $clone;
                    }
                    elseif ($mixable[0] === 'extends') {
                        $this->rule->addExtendSelectors($mixable[1]);
                    }
                    else {
                        $newSet[] = new Declaration($mixable[0], $mixable[1], count($newSet));
                    }
                }
            }
            else {
                $declaration->index = count($newSet);
                $newSet[] = $declaration;
            }
        }

        $this->reset($newSet);
        $this->flattened = true;
    }

    public function process()
    {
        if ($this->processed) {
            return;
        }

        foreach ($this->store as $index => $declaration) {

            // Execute functions, store as data etc.
            $declaration->process($this->rule);

            // Drop declaration if value is now empty.
            if (! $declaration->valid) {
                unset($this->store[$index]);
            }
        }

        // data is done with, reclaim memory.
        unset($this->data);

        $this->processed = true;
    }

    public function expandData($dataset, $property)
    {
        // Expand shorthand properties to make them available
        // as data for this() and query().
        static $expandables = [
            'margin-top' => 'margin',
            'margin-right' => 'margin',
            'margin-bottom' => 'margin',
            'margin-left' => 'margin',
            'padding-top' => 'padding',
            'padding-right' => 'padding',
            'padding-bottom' => 'padding',
            'padding-left' => 'padding',
            'border-top-width' => 'border-width',
            'border-right-width' => 'border-width',
            'border-bottom-width' => 'border-width',
            'border-left-width' => 'border-width',
            'border-top-left-radius' => 'border-radius',
            'border-top-right-radius' => 'border-radius',
            'border-bottom-right-radius' => 'border-radius',
            'border-bottom-left-radius' => 'border-radius',
            'border-top-color' => 'border-color',
            'border-right-color' => 'border-color',
            'border-bottom-color' => 'border-color',
            'border-left-color' => 'border-color',
        ];

        $dataset =& $this->{$dataset};
        $property_group = isset($expandables[$property]) ? $expandables[$property] : null;

        // Bail if property non-expandable or already set.
        if (! $property_group || isset($dataset[$property]) || ! isset($dataset[$property_group])) {
            return;
        }

        // Get the expandable property value.
        $value = $dataset[$property_group];

        // Top-Right-Bottom-Left "trbl" expandable properties.
        $trbl_fmt = null;
        switch ($property_group) {
            case 'margin':
                $trbl_fmt = 'margin-%s';
                break;
            case 'padding':
                $trbl_fmt = 'padding-%s';
                break;
            case 'border-width':
                $trbl_fmt = 'border-%s-width';
                break;
            case 'border-radius':
                $trbl_fmt = 'border-%s-radius';
                break;
            case 'border-color':
                $trbl_fmt = 'border-%s-color';
                break;
        }
        if ($trbl_fmt) {
            $parts = explode(' ', $value);
            $placeholders = [];

            // 4 values.
            if (isset($parts[3])) {
                $placeholders = $parts;
            }
            // 3 values.
            elseif (isset($parts[2])) {
                $placeholders = [$parts[0], $parts[1], $parts[2], $parts[1]];
            }
            // 2 values.
            elseif (isset($parts[1])) {
                $placeholders = [$parts[0], $parts[1], $parts[0], $parts[1]];
            }
            // 1 value.
            else {
                $placeholders = array_pad($placeholders, 4, $parts[0]);
            }

            // Set positional variants.
            if ($property_group === 'border-radius') {
                $positions = [
                    'top-left',
                    'top-right',
                    'bottom-right',
                    'bottom-left',
                ];
            }
            else {
                $positions = [
                    'top',
                    'right',
                    'bottom',
                    'left',
               ];
            }

            foreach ($positions as $index => $position) {
                $prop = sprintf($trbl_fmt, $position);
                $dataset += [$prop => $placeholders[$index]];
            }
        }
    }
}
