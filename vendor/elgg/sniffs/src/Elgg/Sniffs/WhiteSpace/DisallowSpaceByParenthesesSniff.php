<?php

/**
 * Elgg_Sniffs_WhiteSpace_DisallowSpaceByParenthesesSniff.
 *
 * Throws errors if spaces are used immediately after open parens.
 *
 */
class Elgg_Sniffs_WhiteSpace_DisallowSpaceByParenthesesSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$parenthesisOpeners;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $openPtr = $tokens[$stackPtr]['parenthesis_opener'];
        $afterOpenToken = $tokens[($openPtr + 1)];

        $closePtr = $tokens[$stackPtr]['parenthesis_closer'];
        $beforeCloseToken = $tokens[($closePtr -1)];

	$allowedWhitespace = array("\n", "\t");

        $errorLocations = array();

        // don't allow a space after the paren, but do allow a new line or tab
        // only look at the char immediately before
        if ($afterOpenToken['code'] === T_WHITESPACE && !in_array(substr($afterOpenToken['content'], 0, 1), $allowedWhitespace)) {
            $errorLocations[] = 'after opening';
        }

        if ($beforeCloseToken['code'] === T_WHITESPACE && !in_array(substr($beforeCloseToken['content'], -1, 1), $allowedWhitespace)) {
            $errorLocations[] = 'before closing';
        }

        if ($errorLocations) {
            $paren = count($errorLocations) > 1 ? 'parentheses' : 'parenthesis';

            $locations = implode(' or ', $errorLocations);
            $tokenName = $tokens[$stackPtr]['content'];

            $msg = "Spaces not allowed in '$tokenName' $locations $paren.";
            $phpcsFile->addError($msg, $stackPtr); 
        }

    }//end process()


}//end class

?>
