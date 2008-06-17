<?php

	// LDAP authentication module
	
	/*
	 * Revised LDAP module by Victor Rajewski <askvictor@gmail.com>
	 * Based on LDAP module found in standard elgg distribution on 30/6/2007
	 *
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
	 * // Base DN - can be string or array of string for multiple DNs
	 * $CFG->ldap_basedn = 'dc=curverider,dc=co,dc=uk';
	 * $CFG->ldap_basedn = array('dc=curverider,dc=co,dc=uk', 'dc=bucketrider,dc=co,dc=uk');
	 * // Bind as
	 * $CFG->ldap_bind_dn = 'cn=admin,dc=curverider,dc=co,dc=uk';
	 * // Password for non anonymous bind
	 * $CFG->ldap_bind_pwd = 'secret';
	 * // Protocol version
	 * $CFG->ldap_protocol_version = 3;
	 * // Filter for username, common are cn, uid or sAMAccountName
	 * $CFG->ldap_filter_attr = 'uid';
	 * // Search attibutes: associative array with the key being the attribute
	 *    description, and the value being the actual LDAP attribute. firstname
	 *    lastname and mail are used to create the elgg user profile. The
	 *    example below works for ActiveDirectory.
	 * $CFG->ldap_search_attr = array('firstname' => 'givenname',
	 *                                'lastname' => 'sn',
	 *                                'mail' => 'mail');
	 * // Create user, relies on the givenname, sn, and email attributes for now
	 * $CFG->ldap_user_create = true;
	 * // Fallback option, try internal authentication if everything fails
	 * $CFG->ldap_internal_fallback = true
	 */
	
	/**
	 * Sets up the LDAP connection and returns the LDAP link resource, or
	 * null on failure
	 */

	function ldap_init_connection($host, $port, $protocol_version, $bind_dn='', $bind_pwd='') {

		global $messages;

        // Setup the connection

        $ds = @ldap_connect($host, $port);

        @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $protocol_version);

        // Start the LDAP bind process

        $ldapbind = null;

        if ($ds) {
            if ($bind_dn != '') {
                $ldapbind = @ldap_bind($ds, $bind_dn, $bind_pwd);
            } else {
                // Anonymous bind
                $ldapbind = @ldap_bind($ds);
            }
        } else {
            // Unable to connect
            $messages[] = 'Unable to connect to the LDAP server, please contact your system administrator. Error: '.ldap_error($ds);
        }

        if (! $ldapbind) {
            $messages[] = 'Unable to bind to the LDAP server with your credentials, please contact your system administrator. LDAP error: '.ldap_error($ds);

            ldap_close($ds);
        }

        return $ds;
	}

	/**
	 * Attempts to find the username and password in the provided DN, and if 
	 * found tries to bind using the provided password to see if correct
	 */

	function ldap_do_auth($ds, $basedn, $username, $password, $filter_attr, $search_attr) {

	global $messages;

        $sr = @ldap_search($ds, $basedn, $filter_attr ."=". $username, array_values($search_attr));

        if(! $sr) {
	        $messages[] = 'Unable to perform LDAP search, please contact your system administrator. LDAP error: '.ldap_error($ds);

	        return false;
		}

        $entry = ldap_get_entries($ds, $sr);

        if(! $entry or ! $entry[0]) {
        	return false; // didn't find username
		}

        // Username exists
        // Perform a bind for testing credentials

        if (@ldap_bind($ds, $entry[0]['dn'], $password) ) {

            // We have a bind, valid login
            //$messages[] = "Successful LDAP login for ".$entry[0]['dn'];

            foreach (array_keys($search_attr) as $attr) {
	        	$ldap_user_info[$attr] = $entry[0][$search_attr[$attr]][0];
			}

            return $ldap_user_info;
		}

        // Wrong password
        $messages[] = 'Wrong LDAP password. LDAP error: '.ldap_error($ds);

        return false;
	}

	/**
	  * creates an entry in the elgg database for the given username and 
	  * password and LDAP entry
	  */

	function ldap_create_elgg_user($username, $password, $user_info) {

		global $messages;

		if(!validate_username($username)) {
            $messages[] = __gettext("Error! LDAP Username does not meet Elgg requirements");
        } else {
            // Does the user already exist?
            $username = strtolower($username);

            if (record_exists('users','username',$username)) {
                $messages[] = sprintf(__gettext("The username %s is already taken by another user. You will need to pick a different one."), $username);
            } else {
                // Everythink OK, create user
                $user = new StdClass;
                $user->email = $user_info["mail"];
                $user->name  = $user_info["firstname"];
                $user->name  = $user->name . " " . $user_info["lastname"];
                $user->username = $username;
                $user->password = md5($password);
                $user->user_type = 'person';
                $user->owner = -1;

                $user = plugin_hook("user", "create", $user);
                
                if (!empty($user)) {
                    $user_id = insert_record('users', $user);

                    if (!empty($user_id)) {
                        $user->ident = $user_id;
                        // adds "virtual" friend, so that user has at least one connection
                        $owner = 0;
                        $f = new StdClass;
                        $f->owner = $owner;
                        $f->friend = $user_id;
                        insert_record('friends',$f);
                        $f->owner = $user_id;
                        $f->friend = $owner;
                        insert_record('friends',$f);

                        $user = plugin_hook("user", "publish", $user);
                        
                        $rssresult = run("weblogs:rss:publish", array($user_id, false));
                        $rssresult = run("files:rss:publish", array($user_id, false));
                        $rssresult = run("profile:rss:publish", array($user_id, false));
                        
                        $messages[] = sprintf(__gettext("User %s was created."), $username);
                    } else {
                        // User creation failed
                        $messages[] = sprintf(__gettext("User addition %d failed: Unknown reason, please contact you system administrator."), $username);
                    }
                } else {
                    $messages[] = sprintf(__gettext("User addition %d failed: an event listener failed to return the object."), $username);
                }
            }
        }
	}

	/** 
	  * Sets up configuration variables and puts together the above functions
	  * to perform an authentication
	  */

    function ldap_authenticate_user_login($username, $password) {
        global $CFG, $messages;

        if (!function_exists('ldap_connect')) {
            $messages[] = 'No PHP LDAP module available, please contact the system administrator.';
            return false;
        }

		/////////// Set up config //////////////

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

        // Base DN setup
        if(!$CFG->ldap_basedn) {
	        $CFG->ldap_basedn = array ();
        } else {
            if (!is_array($CFG->ldap_basedn)) { //single DN specified
				$CFG->ldap_basedn = array($CFG->ldap_basedn);
			}
		}

        // Which filter to apply for the username, e.g. cn or uid
        if (!$CFG->ldap_filter_attr) {
            $CFG->ldap_filter_attr = 'uid';
        }

        // Which search attributes to return
        if (!$CFG->ldap_search_attr) {
            $CFG->ldap_search_attr = array('dn' => 'dn');
        }

        // Set protocol version, default is v3
        $version = 3;

        // Set up LDAP protocol version

        if ($CFG->ldap_protocol_version) {
            $version = $CFG->ldap_protocol_version;
        }

        ////////// Done setting up config /////////

        //connect and bind
        $ds = ldap_init_connection($CFG->ldap_host, $CFG->ldap_port,
								   $CFG->ldap_protocol_version, 
								   $CFG->ldap_bind_dn,
								   $CFG->ldap_bind_pwd);

		if (! $ds) {
			return false;
		}

        // Perform LDAP search
        foreach ($CFG->ldap_basedn as $this_ldap_basedn) {
        	$ldap_user_info = ldap_do_auth($ds, $this_ldap_basedn, $username, $password, $CFG->ldap_filter_attr, $CFG->ldap_search_attr);

        	if($ldap_user_info) {
        		// LDAP login successful

            	// If we need to create the user

            	if (username_is_available($username) && $CFG->ldap_user_create == true) {
            		ldap_create_elgg_user($username, $password,$ldap_user_info);
				}

            	ldap_close($ds);

            	// Return the user object

	            return get_record_select('users', "username = ? AND active = ? AND user_type = ? ", array($username,'yes','person'));
			}
        }

        // Done with LDAP
        ldap_close($ds);

        // No such user in LDAP, fallback to internal authentication

        if ($CFG->ldap_internal_fallback == true) {
            require_once($CFG->dirroot . 'auth/internal/lib.php');

            return internal_authenticate_user_login($username, $password);
        } else {
            return false;
        }
    }

?>
