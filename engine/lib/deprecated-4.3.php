<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.3
 */

/**
 * Register a PAM handler.
 *
 * A PAM handler should return true if the authentication attempt passed. For a
 * failure, return false or throw an exception. Returning nothing indicates that
 * the handler wants to be skipped.
 *
 * Note, $handler must be string callback (not an array/Closure).
 *
 * @param string $handler    Callable global handler function in the format ()
 * 		                     pam_handler($credentials = null);
 * @param string $importance The importance - "sufficient" (default) or "required"
 * @param string $policy     The policy type, default is "user"
 *
 * @return bool
 * @deprecated 4.3 use elgg_register_pam_handler()
 */
function register_pam_handler($handler, $importance = 'sufficient', $policy = 'user') {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_register_pam_handler()', '4.3');
	
	return elgg_register_pam_handler($handler, $importance, $policy);
}

/**
 * Unregisters a PAM handler.
 *
 * @param string $handler The PAM handler function name
 * @param string $policy  The policy type, default is "user"
 *
 * @return void
 * @since 1.7.0
 * @deprecated 4.3 use elgg_unregister_pam_handler()
 */
function unregister_pam_handler($handler, $policy = 'user') {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_unregister_pam_handler()', '4.3');
	
	elgg_unregister_pam_handler($handler, $policy);
}

/**
 * Perform user authentication with a given username and password.
 *
 * @warning This returns an error message on failure. Use the identical operator to check
 * for access: if (true === elgg_authenticate()) { ... }.
 *
 * @see login()
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
 * @internal
 * @deprecated 4.3 use elgg_pam_authenticate()
 */
function elgg_authenticate($username, $password) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_pam_authenticate()', '4.3');
	
	$pam = new \ElggPAM('user');
	$credentials = ['username' => $username, 'password' => $password];
	$result = $pam->authenticate($credentials);
	if (!$result) {
		return $pam->getFailureMessage();
	}
	
	return true;
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code()
 * @deprecated 4.3 use elgg_generate_invite_code()
 */
function generate_invite_code($username) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_generate_invite_code()', '4.3');
	
	return elgg_generate_invite_code((string) $username);
}

/**
 * Get external resource descriptors
 *
 * @param string $type     Type of file: js or css
 * @param string $location Page location
 *
 * @return array
 * @since 1.8.0
 * @deprecated 4.3 use elgg_get_loaded_external_resources()
 */
function elgg_get_loaded_external_files(string $type, string $location): array {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_get_loaded_external_resources()', '4.3');
	
	return _elgg_services()->externalFiles->getLoadedFiles($type, $location);
}

/**
 * Get the size of the specified directory.
 *
 * @param string $dir        The full path of the directory
 * @param int    $total_size Add to current dir size
 *
 * @return int The size of the directory in bytes
 *
 * @deprecated 4.3
 */
function get_dir_size($dir, $total_size = 0, $show_deprecation_notice = true) {
	if ($show_deprecation_notice) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', '4.3');
	}
	if (!is_dir($dir)) {
		return $total_size;
	}
	
	$handle = opendir($dir);
	while (($file = readdir($handle)) !== false) {
		if (in_array($file, ['.', '..'])) {
			continue;
		}
		if (is_dir($dir . $file)) {
			$total_size = get_dir_size($dir . $file . "/", $total_size, false);
		} else {
			$total_size += filesize($dir . $file);
		}
	}
	closedir($handle);

	return($total_size);
}

/**
 * Filter tags from a given string based on registered hooks.
 *
 * @param mixed $var Anything that does not include an object (strings, ints, arrays)
 *					 This includes multi-dimensional arrays.
 *
 * @return mixed The filtered result - everything will be strings
 *
 * @deprecated 4.3 use elgg_sanitize_input()
 */
function filter_tags($var) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_sanitize_input().', '4.3');
	
	return elgg_sanitize_input($var);
}

/**
 * Takes a string and turns any URLs into formatted links
 *
 * @param string $text The input string
 *
 * @return string The output string with formatted links
 *
 * @deprecated 4.3 use elgg_parse_urls()
 */
function parse_urls($text) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_parse_urls().', '4.3');
	
	return _elgg_services()->html_formatter->parseUrls($text);
}

/**
 * Returns the current page's complete URL.
 *
 * It uses the configured site URL for the hostname rather than depending on
 * what the server uses to populate $_SERVER.
 *
 * @return string The current page URL.
 *
 * @deprecated 4.3 use elgg_get_current_url()
 */
function current_page_url() {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_current_url().', '4.3');
	
	return _elgg_services()->request->getCurrentURL();
}

/**
 * Validates an email address.
 *
 * @param string $address Email address.
 *
 * @return bool
 *
 * @deprecated 4.3 use elgg_is_valid_email()
 */
function is_email_address($address) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_is_valid_email().', '4.3');
	
	return _elgg_services()->accounts->isValidEmail($address);
}

/**
 * Takes in a comma-separated string and returns an array of tags
 * which have been trimmed
 *
 * @param string $string Comma-separated tag string
 *
 * @return mixed An array of strings or the original data if input was not a string
 *
 * @deprecated 4.3 use elgg_string_to_array()
 */
function string_to_tag_array($string) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_string_to_array().', '4.3');
	
	if (!is_string($string)) {
		return $string;
	}
	
	return elgg_string_to_array($string);
}

/**
 * Add a translation.
 *
 * Translations are arrays in the Zend Translation array format, eg:
 *
 *	$english = array('message1' => 'message1', 'message2' => 'message2');
 *  $german = array('message1' => 'Nachricht1','message2' => 'Nachricht2');
 *
 * @param string $country_code   Standard country code (eg 'en', 'nl', 'es')
 * @param array  $language_array Formatted array of strings
 *
 * @return bool Depending on success
 *
 * @deprecated 4.3 use elgg()->translator->addTranslation()
 */
function add_translation($country_code, $language_array) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg()->translator->addTranslation().', '4.3');
	
	return _elgg_services()->translator->addTranslation($country_code, $language_array);
}

/**
 * Get the current system/user language or "en".
 *
 * @return string The language code for the site/user or "en" if not set
 *
 * @deprecated 4.3 use elgg_get_current_language()
 */
function get_current_language() {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_current_language().', '4.3');
	
	return _elgg_services()->translator->getCurrentLanguage();
}
