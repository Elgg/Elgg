<?php

	/**
	 * Elgg language module
	 * Functions to manage language and translations.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Add a translation.
	 * 
	 * Translations are arrays in the Zend Translation array format, eg:
	 * 
	 *	$english = array('message1' => 'message1', 'message2' => 'message2');
	 *  $german = array('message1' => 'Nachricht1','message2' => 'Nachricht2');
	 *
	 * @param string $country_code Standard country code (eg 'en', 'nl', 'es')
	 * @param array $language_array Formatted array of strings
	 * @return true|false Depending on success
	 */

		function add_translation($country_code, $language_array) {
			
			global $CONFIG;
			if (!isset($CONFIG->translations))
				$CONFIG->translations = array();
			
			$country_code = strtolower($country_code);
			$country_code = trim($country_code);
			if (is_array($language_array) && sizeof($language_array) > 0 && $country_code != "") {
				
				if (!isset($CONFIG->translations[$country_code])) {
					$CONFIG->translations[$country_code] = $language_array;
				} else {
					$CONFIG->translations[$country_code] = array_merge($CONFIG->translations[$country_code],$language_array);
				}
				return true;
				
			}
			return false;
			
		}
		
	/**
	 * Detect the current language being used by the current site or logged in user.
	 *
	 */
	function get_current_language()
	{
		global $CONFIG;
		
		$user = get_loggedin_user();
		
		if ((isset($user)) && ($user->language))
			$language = $user->language;
	
		if ((empty($language)) && (isset($CONFIG->language)))
			$language = $CONFIG->language;
			
		if (empty($language))
			$language = 'en';
			
		return $language;
	}
		
	/**
	 * Given a message shortcode, returns an appropriately translated full-text string 
	 *
	 * @param string $message_key The short message code
	 * @param string $language Optionally, the standard language code (defaults to the site default, then English)
	 * @return string Either the translated string, or the original English string, or an empty string
	 */
		function elgg_echo($message_key, $language = "") {
			
			global $CONFIG;

			$user = get_loggedin_user();
			
			if ((empty($language)) && (isset($user)) && ($user->language))
				$language = $user->language;
	
			if ((empty($language)) && (isset($CONFIG->language)))
				$language = $CONFIG->language;
				
			if (isset($CONFIG->translations[$language][$message_key])) {
				return $CONFIG->translations[$language][$message_key];
			} else if (isset($CONFIG->translations["en"][$message_key])) {
				return $CONFIG->translations["en"][$message_key];
			}
				
			return $message_key;
			
		}
		
	/**
	 * When given a full path, finds translation files and loads them
	 *
	 * @param string $path Full path
	 */
		function register_translations($path) {
			global $CONFIG;
			
			if (isset($CONFIG->debug) && $CONFIG->debug == true) error_log("Translations loaded from : $path");
			
			if ($handle = opendir($path)) {
				while ($language = readdir($handle)) {
					if (!in_array($language,array('.','..','.svn','CVS', '.DS_Store', 'Thumbs.db',)) && !is_dir($path . $language)) {
						@include($path . $language);
					}
				}
			}
			else
				error_log("Missing translation path $path");
		}
		
	/**
	 * Return an array of installed translations as an associative array "two letter code" => "native language name".
	 */
		function get_installed_translations()
		{
			global $CONFIG;
			
			$installed = array();
			
			foreach ($CONFIG->translations as $k => $v)
			{
				$installed[$k] = elgg_echo($k, $k);
				
				$completeness = get_language_completeness($k);
				if ((isadminloggedin()) && ($completeness<100) && ($k!='en'))
					$installed[$k] .= " (" . $completeness . "% " . elgg_echo('complete') . ")";
			}
			
			return $installed;
		}
		
	/**
	 * Return the level of completeness for a given language code (compared to english)
	 */
		function get_language_completeness($language)
		{
			global $CONFIG;
			
			$language = sanitise_string($language);
			
			$en = count($CONFIG->translations['en']);
			
			$missing = get_missing_language_keys($language);
			if ($missing) $missing = count($missing); else $missing = 0;
			
			//$lang = count($CONFIG->translations[$language]);
			$lang = $en - $missing;
			
			return round(($lang / $en) * 100, 2);
		}
		
	/**
	 * Return the translation keys missing from a given language, or those that are identical to the english version.
	 */
		function get_missing_language_keys($language)
		{
			global $CONFIG;
			
			$missing = array();
			
			foreach ($CONFIG->translations['en'] as $k => $v)
			{
				if ((!isset($CONFIG->translations[$language][$k])) 
				|| ($CONFIG->translations[$language][$k] == $CONFIG->translations['en'][$k])) 
					$missing[] = $k;
			}
			
			if (count($missing))
				return $missing;
				
			return false;
		}
		
		register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");

?>