<?php

    // LDAP authentication module
    
    /*
     * Basic behaviour:
     *
     * Only if a user exists in LDAP authentication will be processed in this
     * module. In all other cases it will fall back to the internal method.
     *
     * To enable, set $CFG->auth = 'ldap' in config.php
     *
     * Configuration parameters in config.php:
     *
     * // LDAP host
     * $CFG->ldap_host = 'localhost';
     * // LDAP port
     * $CFG->ldap_port = 389;
     * // Base DN
     * $CFG->ldap_basedn = 'dc=curverider,dc=co,dc=uk';
     * // Bind as
     * $CFG->ldap_bind_dn = 'cn=admin,dc=curverider,dc=co,dc=uk';
     * // Password for non anonymous bind
     * $CFG->ldap_bind_pwd = 'secret';
     * // Protocol version
     * $CFG->ldap_protocol_version = 3;
     * // Filter for username, common are cn or uid
     * $CFG->ldap_filter_attr = 'uid';
     * // Search attibutes
     * $CFG->ldap_search_attr = array('dn', 'ou', 'mail');
     * // Create user, relies on the givenname, sn, and email attributes for now
     * $CFG->ldap_user_create = true;
     */

    function ldap_authenticate_user_login($username, $password) {
        global $CFG, $messages;

        if (!function_exists(ldap_connect)) {
            $messages[] = 'No PHP LDAP module available, please contact the system administrator.';
            return false;
        }

        // LDAP host
        if (!$CFG->ldap_host) {
            // No host defined, switch to plain login
            require_once($CFG->dirroot . 'auth/internal/lib.php');
            return internal_authenticate_user_login($username, $password);
        }

        // LDAP port
        if (!$CFG->ldap_port) {
            $CFG->ldap_port = 389;
        }

        // Which filter to apply for the username, e.g. cn or uid
        if (!$CFG->ldap_filter_attr) {
            $CFG->ldap_filter_attr = 'uid';
        }

        // Which search attributes to return
        if (!$CFG->ldap_search_attr) {
            $CFG->ldap_search_attr = array('dn');
        }

        // Setup the connection
        $ds = @ldap_connect($CFG->ldap_host, $CFG->ldap_port);

        // Set protocol version, default is v3
        $version = 3;

        // LDAP protocol version
        if ($CFG->ldap_protocol_version) {
            $version = $CFG->ldap_protocol_version;
        }
                
        @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $version);

        // Start the LDAP bind process
        $ldapbind = null;
    
        if ($ds) {
            if ($CFG->ldap_bind_dn != '') {
                $ldapbind = @ldap_bind($ds, $CFG->ldap_bind_dn, $CFG->ldap_bind_pwd);
            } else {
                // Anonymous bind
                $ldapbind = @ldap_bind($ds);
            }
        } else {
            // Unable to connect
            $messages[] = 'Unable to bind to the LDAP server, please contact your system administrator. Error: '.ldap_error($ds);
        }

        // Initial bind established
        if ($ldapbind) {
            // Perform LDAP search
            $sr = @ldap_search($ds, $CFG->ldap_basedn, $CFG->ldap_filter_attr ."=". $username, $CFG->ldap_search_attr);

            if ($sr) {
                $entry = ldap_get_entries($ds, $sr);

                // Username exists
                if ($entry) {
                    if ($entry[0]) {
                        // Perform a bind for testing credentials
                        if (@ldap_bind($ds, $entry[0]['dn'], $password) ) {
                            // We have a bind, valid login
                            //$messages[] = "Succesfull LDAP login for ".$entry[0]['dn'];

                            // If we need to create the user
                            if ($CFG->ldap_user_create == true) {
                                // Valid Elgg username?
                                if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$username)) {
                                    $messages[] = __gettext("Error! Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                                } else {
                                    // Does the user already exist?
                                    $username = strtolower($username);
                                    if (record_exists('users','username',$username)) {
                                        $messages[] = sprintf(__gettext("The username %s is already taken by another user. You will need to pick a different one."), $username);
                                    } else {
                                        // Everythink OK, create user
                                        $user = new StdClass;
                                        $user->email = $entry[0]["mail"][0];
                                        $user->name  = $entry[0]["givenname"][0];
                                        $user->name  = $user->name . " " . $entry[0]["sn"][0];
                                        $user->username = $username;
                                        $user->password = md5($password);
                                        $user->user_type = 'person';
                                        $user->owner = -1;

                                        $user_id = insert_record('users',$user);

                                        if (!empty($user_id)) {
                                            $rssresult = run("weblogs:rss:publish", array($uid, false));
                                            $rssresult = run("files:rss:publish", array($uid, false));
                                            $rssresult = run("profile:rss:publish", array($uid, false));

                                        } else {
                                            // User creation failed
                                            $messages[] = sprintf(__gettext("User addition %d failed: Unknown reason, please contact you system administrator."), $username);
                                        }
                                    }
                                }
                            }

                            // Done with LDAP
                            ldap_close($ds);

                            // Return the user object
                            return get_record_select('users',"username = ? AND active = ? AND user_type = ? ",
                                                     array($username,'yes','person'));
                        } else {
                            // Invalid credentials
                            $messages[] = 'Invalid credentials. LDAP error: '.ldap_error($ds);

                            // Done with LDAP
                            ldap_close($ds);

                            return false;
                        }
                    } else {
                            // Done with LDAP
                            ldap_close($ds);

                        // No such user in LDAP, fallback to internal authentication
                        // TODO make this a configurable option
                        require_once($CFG->dirroot . 'auth/internal/lib.php');
                        return internal_authenticate_user_login($username, $password);
                    }
                }
            } else {
                $messages[] = 'Unable to setup an LDAP connection, please contact your system administrator. LDAP error: '.ldap_error($ds);

                // Done with LDAP
                ldap_close($ds);

                return false;
            }
        } else {
            $messages[] = 'Unable to bind to the LDAP server with your credentials, please contact your system administrator. LDAP error: '.ldap_error($ds);

            // Done with LDAP
            ldap_close($ds);

            return false;
        }
    }

    function ldap_create_user($user)
    {
    }
?>
