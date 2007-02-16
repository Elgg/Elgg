<?php

/**
 * Authenticate a user using mulitple authentication providers
 *
 * <p>Authenticate a user using authentication providers defined 
 * in an ini based file. The name of this file is fixed and 
 * should be called <tt>auth.ini</tt>. Location for this file should
 * be defined in <tt>config.php</tt> in the <tt>$CFG->auth_multiple_ini</tt>
 * directive. It is best to place this file outside of your document 
 * root since it may contain sensitive inforation.</p>
 *
 * <p>This function will try the providers one by one and will stop 
 * if one returns a valid result. Else it will default to basic
 * elgg (database) authentication and return that result.</p>
 *
 * @author Misja Hoebe
 * @since 0.7
 * @package elgg
 * @subpackage elgg.auth.multiple
 * @param string username
 * @param string password
 * @return mixed authentication result
 */
function multiple_authenticate_user_login($username, $password)
{
    global $CFG, $messages;

    $auth_config = null;

    // Check if an auth.ini location is defined
    if(!$CFG->auth_multiple_ini)
    {
        $messages[] = 'No "auth.ini" location defined';

        return false;
    }

    // and if the file exists
    if (!file_exists($CFG->auth_multiple_ini))
    {
        $messages[] = 'File "auth.ini" does not exist';

        return false;
    }
    else
    {
        // Load the file
        $auth_config = parse_ini_file($CFG->auth_multiple_ini, true);
    }

    // Walk through the config values
    foreach ($auth_config as $key => $settings)
    {
        // Set the configuration parameters
        foreach ($settings as $setting => $value)
        {
            $CFG->{$setting} = $value;
        }

        // All done call the provider
        require_once($CFG->dirroot . "auth/$CFG->auth/lib.php");

        $function = $CFG->auth . "_authenticate_user_login";

        $result = $function($username, $password);

        if ($result == false)
        {
            continue;
        }
        else
        {
            // We're happy
            return $result;
        }
    }

    // If we have reached this point no provider has returned true, 
    // so we use the internal authentication code as a final resort

    // Reset to internal
    $CFG->auth = 'internal';

    require_once($CFG->dirroot . "auth/internal/lib.php");

    return internal_authenticate_user_login($username, $password);
}
?>
