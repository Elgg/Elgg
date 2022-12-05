<?php
/**
 *
 *  Balanced bracket matching on string objects.
 *
 * Elgg: fixed PHP8.2 compatibility #[\AllowDynamicProperties]
 */
namespace CssCrush;

#[\AllowDynamicProperties]
class BalancedMatch
{
    public function __construct(StringObject $string, $offset, $brackets = '{}')
    {
        $this->string = $string;
        $this->offset = $offset;
        $this->match = null;
        $this->length = 0;

        list($opener, $closer) = str_split($brackets, 1);

        if (strpos($string->raw, $opener, $this->offset) === false) {

            return;
        }

        if (substr_count($string->raw, $opener) !== substr_count($string->raw, $closer)) {
            $sample = substr($string->raw, $this->offset, 25);
            warning("Unmatched token near '$sample'.");

            return;
        }

        $patt = ($opener === '{') ? Regex::$patt->block : Regex::$patt->parens;

        if (preg_match($patt, $string->raw, $m, PREG_OFFSET_CAPTURE, $this->offset)) {

            $this->match = $m;
            $this->matchLength = strlen($m[0][0]);
            $this->matchStart = $m[0][1];
            $this->matchEnd = $this->matchStart + $this->matchLength;
            $this->length = $this->matchEnd - $this->offset;
        }
        else {
            warning("Could not match '$opener'. Exiting.");
        }
    }

    public function inside()
    {
        return $this->match[2][0];
    }

    public function whole()
    {
        return substr($this->string->raw, $this->offset, $this->length);
    }

    public function replace($replacement)
    {
        $this->string->splice($replacement, $this->offset, $this->length);
    }

    public function unWrap()
    {
        $this->string->splice($this->inside(), $this->offset, $this->length);
    }
}
