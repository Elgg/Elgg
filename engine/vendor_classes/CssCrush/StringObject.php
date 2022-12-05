<?php
/**
 *
 *  String sugar.
 *
 * Elgg: fixed PHP8.2 compatibility #[\AllowDynamicProperties]
 */
namespace CssCrush;

#[\AllowDynamicProperties]
class StringObject
{
    public function __construct($str)
    {
        $this->raw = $str;
    }

    public function __toString()
    {
        return $this->raw;
    }

    public static function endsWith($haystack, $needle)
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    public function update($str)
    {
        $this->raw = $str;

        return $this;
    }

    public function substr($start, $length = null)
    {
        if (! isset($length)) {

            return substr($this->raw, $start);
        }
        else {

            return substr($this->raw, $start, $length);
        }
    }

    public function matchAll($patt, $offset = 0)
    {
        return Regex::matchAll($patt, $this->raw, $offset);
    }

    public function replaceHash($replacements)
    {
        if ($replacements) {
            $this->raw = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $this->raw);
        }
        return $this;
    }

    public function pregReplaceHash($replacements)
    {
        if ($replacements) {
            $this->raw = preg_replace(
                array_keys($replacements),
                array_values($replacements),
                $this->raw);
        }
        return $this;
    }

    public function pregReplaceCallback($patt, $callback)
    {
        $this->raw = preg_replace_callback($patt, $callback, $this->raw);
        return $this;
    }

    public function append($append)
    {
        $this->raw .= $append;
        return $this;
    }

    public function prepend($prepend)
    {
        $this->raw = $prepend . $this->raw;
        return $this;
    }

    public function splice($replacement, $offset, $length = null)
    {
        $this->raw = substr_replace($this->raw, $replacement, $offset, $length);
        return $this;
    }

    public function trim()
    {
        $this->raw = trim($this->raw);
        return $this;
    }

    public function rTrim()
    {
        $this->raw = rtrim($this->raw);
        return $this;
    }

    public function lTrim()
    {
        $this->raw = ltrim($this->raw);
        return $this;
    }

    public function restore($types, $release = false, $callback = null)
    {
        $this->raw = Crush::$process->tokens->restore($this->raw, $types, $release, $callback);

        return $this;
    }

    public function captureDirectives($directive, $parse_options = [])
    {
        if (is_array($directive)) {
            $directive = '(?:' . implode('|', $directive) . ')';
        }

        $parse_options += [
            'keyed' => true,
            'lowercase_keys' => true,
            'ignore_directives' => true,
            'singles' => false,
            'flatten' => false,
        ];

        if ($parse_options['singles']) {
            $patt = Regex::make('~@(?i)' . $directive . '(?-i)(?:\s*{{ block }}|\s+(?<name>{{ ident }})\s+(?<value>[^;]+)\s*;)~S');
        }
        else {
            $patt = Regex::make('~@(?i)' . $directive . '(?-i)\s*{{ block }}~S');
        }

        $captured_directives = [];
        $this->pregReplaceCallback($patt, function ($m) use (&$captured_directives, $parse_options) {
            if (isset($m['name'])) {
                $name = $parse_options['lowercase_keys'] ? strtolower($m['name']) : $m['name'];
                $captured_directives[$name] = $m['value'];
            }
            else {
                $captured_directives = DeclarationList::parse($m['block_content'], $parse_options) + $captured_directives;
            }
            return '';
        });

        return $captured_directives;
    }
}
