<?php

	// If gettext isn't installed, define a function placeholder for gettext()

		if (!function_exists("gettext")) {
			function gettext($input) {
				return $input;
			}
			define("gettext", false);
		} else {
			define("gettext", true);
		}
		
	// If gettext is installed, define path for languages, set language etc
		if (gettext == true) {
			
			// If default language isn't specified, it's English
			if (!defined("locale")) {
				define("locale", "en");
			}
			
			// If the user's browser hasn't set a language, set it to be the default locale
			// If it has, create a list of languages in preference order
			$list = array();

			if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || $_SERVER['HTTP_ACCEPT_LANGUAGE'] == "") {
				$locale = strtolower(locale);
				$list[] = $locale;
			} else {
				$locale = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				$list = explode(",", $locale);
			}
			
			// If the locale isn't the default, set a new default language
			if ($list[0] != locale) {
				// Bind the 'elgg' text domain to the languages directory
				bindtextdomain ('elgg', path . 'languages');
				// Set our current text domain to 'elgg'
				textdomain ('elgg');
				// Set locale to country_COUNTRY in preference order
				if (sizeof($list) > 0) {
					foreach($list as $language) {
						$language = explode(";",$language);
						$language = $language[0];
						if (setlocale(LC_MESSAGES, $language . "_" . strtoupper($language))) {
							setlocale(LC_TIME, $language . "_" . strtoupper($language));
							break;
						}
					}
				}
			}
			
		}

?>