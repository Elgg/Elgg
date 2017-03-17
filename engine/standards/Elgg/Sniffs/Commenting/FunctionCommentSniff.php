<?php

class Elgg_Sniffs_Commenting_FunctionCommentSniff extends \PEAR_Sniffs_Commenting_FunctionCommentSniff {

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		foreach ($tokens as $index => $token) {
			if ($token['content'] == '@access' && $tokens[$index + 2]['content'] == 'private') {
				return;
			}
		}
		return parent::process($phpcsFile, $stackPtr);
	}

}
