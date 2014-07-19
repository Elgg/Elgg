<?php

/**
 * Elgg_Sniffs_Strings_ConcatenationSpacingSniff.
 *
 * Makes sure there are spaces between the concatenation operator (.) and
 * the strings being concatenated.
 *
 */
class Elgg_Sniffs_Strings_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING_CONCAT);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $found    = '';
        $expected = '';
        $error    = false;

        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
            $expected .= '...' . substr($tokens[($stackPtr - 1)]['content'], -10) . ' ' . $tokens[$stackPtr]['content'];
            $found    .= '...' . substr($tokens[($stackPtr - 1)]['content'], -10) . $tokens[$stackPtr]['content'];
            $error     = true;
        } else {
            $found    .= '...' . substr($tokens[($stackPtr - 2)]['content'] . $tokens[($stackPtr -1)]['content'] . $tokens[$stackPtr]['content'], -10);
            $expected .= '...' . substr($tokens[($stackPtr - 2)]['content'] . $tokens[($stackPtr -1)]['content'] . $tokens[$stackPtr]['content'], -10);
        }

        if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
            $expected .= ' ' . substr($tokens[($stackPtr + 1)]['content'], 0, 10) . '...';
            $found    .= substr($tokens[($stackPtr + 1)]['content'] . $tokens[($stackPtr + 2)]['content'], 0, 10) . '...';
            $error     = true;
        } else {
            $found    .= substr($tokens[($stackPtr + 1)]['content'] . $tokens[($stackPtr + 2)]['content'], 0, 10) . '...';
            $expected .= substr($tokens[($stackPtr + 1)]['content'] . $tokens[($stackPtr + 2)]['content'], 0, 10) . '...' ;
        }

        if ($error === true) {
            $found    = str_replace("\r\n", '\n', $found);
            $found    = str_replace("\n", '\n', $found);
            $found    = str_replace("\r", '\n', $found);
            $expected = str_replace("\r\n", '\n', $expected);
            $expected = str_replace("\n", '\n', $expected);
            $expected = str_replace("\r", '\n', $expected);

            $message = "Concat operator must be surrounded by spaces. Found \"$found\"; expected \"$expected\"";
            $phpcsFile->addError($message, $stackPtr);
        }

    }//end process()
}//end class

?>
