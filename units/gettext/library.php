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
				define("locale", "en_GB");
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
			// If the first choice is the right non-country-specific language, don't bother
			// (kludge to allow a browser to default to "en", basically)
			if (substr($list[0], 0, 2) != substr(locale, 0, 2)) {
				// Bind the 'elgg' text domain to the languages directory
				$textdomainpath = bindtextdomain ('elgg', path . 'languages');
				// Set our current text domain to 'elgg'
				textdomain ('elgg');
				// Set locale to language_COUNTRY in preference order
				if (sizeof($list) > 0) {
					$newlist = array();
					$extralist = array();
					foreach ($list as $language) {
						$language = explode(";", $language);
						$language = $language[0];
						$languageparts = explode("-", $language);
						if ($languageparts[1]) {
							// If the browser has given a 2-part language code, use it...
							$newlist[] = $languageparts[0] . "_" . strtoupper($languageparts[1]);
							// but also add the munged code to the end of the list, just in case.
							// E.g. if user passes "de_AT", this will add "de_DE" to end of the list as a fallback.
							$extralist[] = $languageparts[0] . "_" . strtoupper($languageparts[0]);
						} else {
							// Otherwise munge one from a 1-part code.
							// NB. This is a flawed assumption, because not all languages have the same language 
							// and country codes: e.g. en_EN is not valid for English, and Danish is da_DK.
							$newlist[] = $languageparts[0] . "_" . strtoupper($languageparts[0]);
						}
					}
					$list = array_merge($newlist, $extralist);
					
					foreach($list as $languagecode) {
						// Presumably we don't want to set locale to a language we don't have the .mo file for?
						if (file_exists($textdomainpath . '/' . $languagecode . '/LC_MESSAGES/elgg.mo')) {
							if (setlocale(LC_MESSAGES, $languagecode)) {
								setlocale(LC_TIME, $languagecode);
								break;
							}
						}
					}
				}
			}
			
		}

?>