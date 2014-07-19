<?php

/**
 * Elgg_Sniffs_Files_LineLengthSniff.
 *
 * Checks all lines in the file and throws warnings if they are over 100.
 */
class Elgg_Sniffs_Files_LineLengthSniff extends Generic_Sniffs_Files_LineLengthSniff
{

    /**
     * The limit that the length of a line should not exceed.
     *
     * @var int
     */
    public $lineLimit = 100;

    /**
     * The limit that the length of a line must not exceed.
     *
     * Set to zero (0) to disable.
     *
     * @var int
     */
    public $absoluteLineLimit = 150;

}//end class

?>
