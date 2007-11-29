<?php

    // Function to authenticate
    
    // Original elgg logon code is not modular enough, most of the data, bussinesslogic and
    // presentation folded in one, plus:
    // - it expects the username and password in the _POST variable, we are dealing with _SERVER.
    // - it sets a display message, we need a run_result
    // - what if we need LDAP, or other ...

    // Note: the HTTP Authentication hooks in PHP are only available when it is running as an Apache 
    // module and is hence not available in the CGI version.

    // The Pear Auth packge looks nice, with hooks for plugging in different
    // authentication providers (DB, LDAP, etc.). Wait for elgg people first.

    
    $auth             = array();
    $auth['status']   = false;
    $auth['message']  = "";
    $auth['code']     = 0;      // not used, for passing generalized result codes, e.g. 3 = no such user (should be used instead of text message, e.g. for being able to construct internationalized message)
    $auth['provider'] = "elgg"; // not used, elgg, ldap, smb, file, sso
    $auth['method']   = "";     // how have credentials been passed: http-basic-auth, post, parameters, token, etc.
    $auth['token']    = "";     // not used, for passing a sso token?

    $username         = "";
    $password         = "";
    $token            = ""; // not used/implemented, for providing an authentication token (e.g. sso) via $parameter['token'] (not sure how this works)?
    $provider         = ""; // not used/implemented, possibility for explicitly requesting an authentication provider via $parameter['provider']?

    // For now parameters take precendence
    
    if (isset($parameter) && $parameter['username'] != "") // parameters passed by run()
    {
        $username = $parameter['username'];
        $password = $parameter['password'];
        
        $auth['method'] = "parameters";
    }
    elseif (isset($_SERVER['HTTP_X_WSSE']) && $_SERVER['HTTP_X_WSSE'] != "")
    {
        // Some basic Web Services Security UsernameToken Profile (WSSE) support
        $wsse = str_replace("UsernameToken","", $_SERVER['HTTP_X_WSSE']);
        $wsse = explode(",", $wsse);

        foreach ($wsse as $element)
        {
            $element = explode("=", $element);
            $key = trim($element[0]);
            $val = trim($element[1],"\x22\x27");

            if ( $key == "Username")
            {
                $username = $val;
            }
            elseif ($key == "PasswordDigest")
            {
                $password = $val;
            }
            elseif ($key == "Created")
            {
                $created = $val;
            }
            elseif ($key == "Nonce")
            {
                $nonce = $val;
            }
        }
        
        $result = get_record('users','username',$username);
        $good_pw = md5($result->password);

        // Recreate the digest
        $digest = pack("H*", sha1($nonce
                                . $created
                                . $good_pw));

        $auth['method'] = $good_pw;
    }
    elseif (isset($_SERVER['PHP_AUTH_USER']) && 
            isset($_SERVER['PHP_AUTH_PW'])   && 
            $_SERVER['PHP_AUTH_USER'] != ""  && 
            $_SERVER['PHP_AUTH_PW'] != "") // Basic HTTP AUTH
    {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = md5($_SERVER['PHP_AUTH_PW']);
        
        $auth['method'] = "http-basic-auth";
    }
    elseif (isset($_POST['username']) &&
            isset($_POST['password']) &&
            $_POST['username'] != "" && 
            $_POST['password'] != "") // parameters passed via login form (form post)
    {        
        $username = trim($_POST['username']);
        $password = trim(md5($_POST['password']));
        
        $auth['method'] = "post";
    }
    // Conditions to be extended for other methods (tokens etc.)
    
    // If all is well we have a username and password
    // To be modified for different providers, tokens, etc. and fall-through (iterate through the configured providers)
    
    // Elgg authentication provider
    
    if (isset($username))
    {
        $logonsuccess = authenticate_account($username,$password);
        if ($logonsuccess)
        {
            $auth['status']  = true;
            $auth['message'] = "Authenticated";
            $auth['code']    = 200;
        }
        else
        {
            $auth['status']  = false;
            $auth['message'] = "Incorrect username or password";
            $auth['code']    = 801;
        }
    }
    else
    {
        $auth['status']  = false;
        $auth['message'] = "No username or password provided";
        $auth['code']    = 801;

    }

    $run_result = $auth;

?>
