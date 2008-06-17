<?php

	if (!defined('LC_MESSAGES'))
	{
		define('LC_MESSAGES', 6);
	}

    // Echo a translated string.
    function __gettext($text, $domain = 'none')
    {
        global $CFG;

        if ($domain == 'none')
        {
            $domain = $CFG->translation_domain;
        }

        // Domain loader
        $i18n = get_domain($domain);

        if ($i18n != null)
        {

            foreach($i18n as $language)
			{
            	if($language->translate($text) != $text)
				{
                	return $language->translate($text);
              	}
            }
            return $text;
        }
        else
        {
            return $text;
        }
    }

    // Return the plural form.
    function __ngettext($single, $plural, $number, $domain = 'elgg')
    {
        // Domain loader
        $i18n = get_domain($domain);

        if ($i18n != null)
        {
            return $i18n->n__gettext($single, $plural, $number);
        }
        else
		{
            if ($number != 1)
            {
                return $plural;
            }
            else
            {
				return $single;
            }
        }
    }

    function get_domain($domain)
    {
        global $CFG, $USER, $l10n;

        if (!isset($USER->locale))
        {
            $USER->locale = $CFG->defaultlocale;
        }

        if (!isset($l10n[$domain]))
        {
            $l10n[$domain] = array();
        }

        if (isset($l10n[$domain][$USER->locale]))
        {
            return $l10n[$domain][$USER->locale];
        }
        else
        {
            if (array_key_exists($USER->locale, $CFG->languages_domain_paths[$domain]))
            {
                rsort($CFG->languages_domain_paths[$domain][$USER->locale]);
                foreach($CFG->languages_domain_paths[$domain][$USER->locale] as $languagedomain)
				{
                  	// Double check if file exists
                	if (file_exists($languagedomain))
					{
                    	$input = new CachedFileReader($languagedomain);
                      	$l10n[$domain][$USER->locale][] = new gettext_reader($input);
                  	}
                }
                return $l10n[$domain][$USER->locale];
            }
            else
			{
            	return;
            }
        }
    }

    function parse_http_accept_language($str = null)
	{
        global $CFG;

        $browser_lang = "";

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
            $browser_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        else
        {
            // No values
            return;
        }

        // getting http instruction if not provided
        $str = $str ? $str : $browser_lang;

        // exploding accepted languages
        $langs = explode(',',$str);

        // creating output list
        $accepted = array();

        foreach ($langs as $lang)
		{
            // parsing language preference instructions
            // 2_digit_code[-longer_code][;q=coefficient]
            ereg('([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?', $lang, $found);

            // 2 digit lang code
            $code = $found[1];

            // lang code complement
            $morecode = $found[3];

            if (empty($morecode))
			{
                $fullcode = $code;
            }
           	else
			{
                $fullcode = $code . "_" . strtoupper($morecode);
            }

            // coefficient
            $coef = sprintf('%3.1f', $found[5] ? $found[5] : '1');

            // for sorting by coefficient
            $key = $coef.'-'.$code;

            $accepted[$key] = array('code'     => $code,
                                    'coef'     => $coef,
                                    'morecode' => $morecode,
                                    'fullcode' => $fullcode);
        }

        // sorting the list by coefficient desc
        krsort($accepted);

        return $accepted;
    }

    // Parse installed languages and add .mo file paths
    function parse_installed_languages($root)
    {
        global $CFG, $messages;

        // Load known language codes
        parse_languages_available();

        // Array to hold domain files, available in $CFG->languages_installed
        // E.g. $CFG->languages_installed['elgg']['nl'] = '/path/to/nl/LC_MESSAGES/elgg.mo'
        $languages = array();

        // Loop through directory content
        // TODO this realy could do with some cacheing

        // Exit if no languages directory available
        if (!is_dir($root . 'languages'))
        {
            return;
        }

        $basedir = opendir($root . 'languages');

        while (false !== ($dir = readdir($basedir)))
        {
            $firstchar = substr($dir, 0, 1);

            if ($firstchar == '.' or $dir == 'CVS' or $dir == '_vti_cnf' or $dir == '.svn')
            {
                continue;
            }

            if (filetype($root . 'languages/'. $dir) != 'dir')
            {
                continue;
            }

            // Language code exists, pick up domain files
            if (array_key_exists($dir, $CFG->languages_available))
            {
                // First add the language to the language_available
                // Note: also plugin translations will get added so
                // one can get partially translated pages
                $CFG->languages_installed[$dir] = $CFG->languages_available[$dir];

                $langdir = opendir($root.'languages/'.$dir.'/LC_MESSAGES');

                while(false !== ($file = readdir($langdir)))
                {
                  $firstchar = substr($file, 0, 1);

                  if ($firstchar == '.' or $file == 'CVS' or $file == '_vti_cnf' or $file == '.svn')
                  {
                      continue;
                  }
                    // Grab the .mo file
                    eregi("([a-zA-Z].*)\.mo", $file, $match);
                    if ($match)
                    {
                        $CFG->languages_domain_paths[$match[1]][$dir][] = $root.'languages/'.$dir.'/LC_MESSAGES/'.$match[0];
                    }
                    $match = null;
                }
            }
            else
            {
                $messages[] = "Unknown language code: ".$dir." in ".$root."languages/. Please ask the system administrator to configure this properly.";
            }
        }
    }

    // Load known language codes from cache
    function parse_languages_available()
    {
        global $CFG;

        // Only load once
        if (!isset($CFG->languages_available))
        {
            $CFG->languages_available = unserialize(file_get_contents(dirname(__FILE__).'/languages.cache'));
        }
        else
        {
            return;
        }
    }

    // Initialize internationalization
    function init_i18n()
    {
        global $CFG, $USER;

        // Clean up the (possible) flaw created by the first svn commit
        // TODO can be removed with next package release
        //delete_records('user_flags','flag','language','user_id',0);
        // End fix

        // Load default language path
        parse_installed_languages($CFG->dirroot);

        // Load plugin language paths
        $plugindir = opendir($CFG->dirroot . 'mod/');
        while (false !== ($dir = readdir($plugindir)))
        {
            $firstchar = substr($dir, 0, 1);

            if ($firstchar == '.' or $dir == 'CVS' or $dir == '_vti_cnf' or $dir == 'README.txt')
            {
                continue;
            }
            else
            {
                parse_installed_languages($CFG->dirroot.'mod/'.$dir.'/');
            }
        }

        // Store the browser setting
        $USER->languages_browser = parse_http_accept_language();

        // Check if $CFG->defaultlocale has been setup correctly
        if (!$CFG->languages_available[$CFG->defaultlocale])
        {
            $bad = $CFG->defaultlocale;

            // Empty it, and send a message to the screen
            $CFG->defaultlocale = '';

            global $messages;

            $messages[] = "Unknown language code: ".$bad.". Please ask the system administrator to properly configure \$CFG->defaultlocale in the main configuration file.";
        }

        // Setup variables to hold the user language
        $USER->locale = $CFG->defaultlocale;

        // set initial session variable
        if (!isset($_SESSION['locale']))
        {
            $_SESSION['locale'] = '';
        }

        // Now, grab user preference


        // logged_on is not yet defined yet in this stage,
        // use USER->ident for this...
        // TODO Implement better check!
        if ($USER->ident != 0)
        {
            // User is logged in and has a session
            if ($result = get_record('user_flags','flag','language','user_id',$USER->ident))
            {
                // TODO fix possible rev. 664 and 663 commit errors
                // Remove for before next package release
                if ($result->value == 'default')
                {
                    $result->value = $CFG->defaultlocale;
                }
                // End fix

                $_SESSION['locale'] = $result->value;
                $USER->locale = $result->value;
            }
            else
            {
                // No flag set yet, try extract from the browser
                $setting = '';

                if (!empty($USER->languages_browser))
                {
                    $keys = array_keys($USER->languages_browser);

                    if ($browser = $USER->languages_browser[$keys[0]])
                    {
                        $setting = $browser['fullcode'];
                    }
                }
                else
                {
                    // No browser preference, get defaultlocale
                    $setting = $CFG->defaultlocale;
                }

                // Store the value
                $flag = new StdClass;
                $flag->flag = 'language';
                $flag->user_id = $USER->ident;
                $flag->value = $setting;
                insert_record('user_flags',$flag);

                $_SESSION['locale'] = $setting;
                $USER->locale = $setting;
            }
        }
        else
        {
            // User is logged out

            // TODO special actions? For now language will be set
            // to $CFG-defaultlocale for non logged in users or via
            // a session, see below.

            // TODO This is here to set the pref via a session for
            // non logged in so they could also use the language
            // selection widget. Should perhaps get stored in a cookie.

            if (isset($_SESSION['locale']) && $_SESSION['locale'] != '')
            {
                $USER->locale = $_SESSION['locale'];
            }
            else
            {
                $USER->locale = $CFG->defaultlocale;
            }
        }

        // Handle explicit language setting via widget. Here for now because
        // else it will get called too late to set the user language.
        $params = explode('?',$_SERVER['REQUEST_URI']);

        if (isset($params[1]))
        {
            $lang = explode('=', $params[1]);
            if ($lang[0] == 'lang')
            {
                if ($lang[1] != 'default' && array_key_exists($lang[1], $CFG->languages_available))
                {
                    $USER->locale = $lang[1];
                }
                else
                {
                    $USER->locale = $CFG->defaultlocale;
                }
            }

            // Store it in the user preferences
            // TODO better logged in check, logged_on not available
            if ($USER->ident != 0)
            {
                // Have to replicate flag setting because of including order

                // unset first
                delete_records('user_flags','flag','language','user_id',$USER->ident);
                // save the flag
                $flag = new StdClass;
                $flag->flag = 'language';
                $flag->user_id = $USER->ident;
                $flag->value = $USER->locale;
                insert_record('user_flags',$flag);
            }

            // Set session variable
            $_SESSION['locale'] = $USER->locale;
        }

        // Set system locale, for future native gettext support
        setlocale(LC_ALL, $USER->locale . '.utf8', $USER->locale);

        // If only language set, add a region for strftime to work properly.
        // Note, this implies system locales installed and is independent
        // from Elgg locales
        $time_locale = $USER->locale;

        if (!substr($USER->locale, 2, 1) == "_")
		{
            $time_locale = $USER->locale . "_" . strtoupper($USER->locale);
        }

        setlocale(LC_TIME, $time_locale . '.utf8', $time_locale);
    }

    function gettext_userdetails_edit_details()
    {
        global $CFG, $USER;

        $title = __gettext("Language selection").":";
        $blurb = __gettext("Choose your preferred language for this site. The following languages are available on this system:");

        $body = <<< END
            <h2>$title</h2>
            <p>$blurb</p>
END;

        $tmp = "";

        if ($CFG->languages_installed == null)
        {
            $tmp = __gettext("No languages installed");
        }
        else
        {

            $tmp .= '<select name="lang">';
            $tmp .= '<option value="default">'.__gettext('default')."</option>\n";

            ksort($CFG->languages_installed);

            foreach ($CFG->languages_installed as $key => $description)
            {
                $selected = '';
                if ($USER->locale == $key)
                {
                    $selected = 'selected="selected"';
                }
                $tmp .= '<option value="'.$key.'" '.$selected.'>'.$description."</option>\n";
            }

            $tmp .= '</select>';
        }

        $body .= templates_draw( array(
                    'context' => 'databox',
                    'name' => $title,
                    'column1' => $tmp
        )
        );

        return $body;
    }

    function gettext_userdetails_actions()
    {
        global $CFG, $USER, $messages;

        $action = optional_param('action');
        $id = optional_param('id',0,PARAM_INT);
        $lang = optional_param('lang', $CFG->defaultlocale, PARAM_ALPHAEXT);
        $lang_db = user_flag_get('language', $id);

        if ($lang == 'default')
        {
            $lang = $CFG->defaultlocale;
        }

        if (logged_on && !empty($action) && run("permissions:check", array("userdetails:change",$id)) && $action == "userdetails:update")
        {
            if (!empty($lang))
            {
                if ($lang != $lang_db)
                {
                    if (user_flag_set('language', $lang, $id))
                    {
                        $_SESSION['locale'] = $lang;
                        $USER->locale = $lang;

                        $messages[] .= __gettext("Preferred language") . " " . __gettext("saved") .".";
                    }
                }
            }
        }
    }

?>
