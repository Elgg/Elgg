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
	 * Given a message shortcode, returns an appropriately translated full-text string 
	 *
	 * @param string $message_key The short message code
	 * @param string $language Optionally, the standard language code (defaults to the site default, then English)
	 * @return string Either the translated string, or the original English string, or an empty string
	 */
		function elgg_echo($message_key, $language = "") {
			
			global $CONFIG;
			
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
			if ($handle = opendir($path)) {
				while ($language = readdir($handle)) {
					if (!in_array($language,array('.','..','.svn','CVS')) && !is_dir($path . $language)) {
						@include($path . $language);
					}
				}
			}
		}
		
		register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");

?>