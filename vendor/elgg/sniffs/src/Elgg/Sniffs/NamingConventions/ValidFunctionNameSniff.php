<?php

/**
 * Elgg_Sniffs_NamingConventions_ValidFunctionNameSniff.
 *
 * Functions should be named like this_is_my_function()
 * Class methods should be named thisIsMyMethod()
 *
 */
class Elgg_Sniffs_NamingConventions_ValidFunctionNameSniff extends PEAR_Sniffs_NamingConventions_ValidFunctionNameSniff
{

    /**
     * Returns true if the specified string is in the underscore lowercase format.
     *
     * @param string $string The string to verify.
     *
     * @return boolean
     */
    protected function isUnderscoreName($string)
    {
        // If there are space in the name, it can't be valid.
        if (strpos($string, ' ') !== false) {
            return false;
        }

        // Remove the first _ if it exists.  This is allowed
        // for very low level "private" internal functions.
        // Only need to check against _ because already looked for __
        if (preg_match('|^_|', $string) === 1) {
            $string = substr($string, 1);
        }

        $validName = true;
        $nameBits  = explode('_', $string);

        if (preg_match('|^[a-z]|', $string) === 0) {
            // Name does not begin with a capital letter.
            $validName = false;
        } else {
            foreach ($nameBits as $bit) {
                if ($bit{0} !== strtolower($bit{0})) {
                    $validName = false;
                    break;
                }
            }
        }

        return $validName;

    }//end isUnderscoreName()

    /**
     * Processes the tokens outside the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int                  $stackPtr  The position where this token was
     *                                        found.
     *
     * @return void
     */
    protected function processTokenOutsideScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if ($functionName === null) {
            return;
        }
        // disallow __function_name() 
        if (preg_match('|^__|', $functionName) === 1) {
            $error = "Function name \"$functionName\" cannot start with '__'.";
            $phpcsFile->addError($error, $stackPtr);
        } else if ($this->isUnderscoreName($functionName) === false) {
            $error = "Function name \"$functionName\" is not in underscore format";
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end processTokenOutsideScope()

    /**
     * Processes the tokens within the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int                  $stackPtr  The position where this token was
     *                                        found.
     * @param int                  $currScope The position of the current scope.
     *
     * @return void
     */
    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        $className  = $phpcsFile->getDeclarationName($currScope);

        // Is this a magic method. IE. is prefixed with "__".
        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = strtolower(substr($methodName, 2));
            if (in_array($magicPart, $this->magicMethods) === false) {
                 $error = "Method name \"$className::$methodName\" is invalid; only PHP magic methods should be prefixed with a double underscore";
                 $phpcsFile->addError($error, $stackPtr);
            }

            return;
        }

        $methodProps    = $phpcsFile->getMethodProperties($stackPtr);
        $isPublic       = ($methodProps['scope'] === 'private') ? false : true;
        $scope          = $methodProps['scope'];
        $scopeSpecified = $methodProps['scope_specified'];

        // If the scope was specified on the method, then the method must be
        // camel caps and an underscore should be checked for. If it wasn't
        // specified, treat it like a public method and remove the underscore
        // prefix if there is one because we cant determine if it is private or
        // public.
        $testMethodName = $methodName;
        if ($scopeSpecified === false && $methodName{0} === '_') {
            $testMethodName = substr($methodName, 1);
        }

        // Elgg modification: force CodeSniffer to see all methods as public
        if (PHP_CodeSniffer::isCamelCaps($testMethodName, false, true, false) === false) {
            if ($scopeSpecified === true) {
                $error = ucfirst($scope)." method name \"$className::$methodName\" is not in underscore and camel caps format";
            } else {
                $error = "Method name \"$className::$methodName\" is not in camel caps format";
            }

            $phpcsFile->addError($error, $stackPtr);
            return;
        }

    }//end processTokenWithinScope()


}//end class

?>
