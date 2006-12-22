<?php

    function gettext_pagesetup()
    {
        global $CFG, $PAGE;

        // No languages to choose from, do nothing 
        if($CFG->languages_installed == null)
        {
            return;
        }

        // Only show language selector if anonymous user
        // Logged in users set their laguage in their preferences

        $tmp  = '<li>'.__gettext("Language", "gettext").": ";
        $tmp .= '<select name="lang" onChange="location = \'?lang=\' + this.options[this.selectedIndex].value;">';
        $tmp .= '<option value="default">'.__gettext("default", "gettext").'</option>' . "\n";
        
        ksort($CFG->languages_installed);
        
        foreach ($CFG->languages_installed as $key => $description)
        {
            $selected = '';
//$USER->locale
            if ($_SESSION['locale'] == $key && $CFG->languages_installed[$key])
            {
                $selected = 'selected="selected"';
            }

            $tmp .= '<option value="'.$key.'" '.$selected.'>'.$description.'</option>' . "\n";
            
        }
        
        /* Show all languages
        
        foreach ($CFG->languages_available as $key => $description)
        {
            $selected = '';
            $disabled = '';

            if ($USER->locale == $key && $CFG->languages_installed[$key])
            {
                $selected = 'selected="selected"';
            }

            if (!array_key_exists($key, $CFG->languages_installed))
            {
                $disabled = 'disabled="disabled"';
            }

            $tmp .= '<option value="'.$key.'" '.$selected.' '.$disabled.'>'.$description.'</option>' . "\n";
            
        }
        */
        
        $tmp .= '</select>';
        $tmp .= '</li>';

        $menu_element = array('name' => 'language', 'html' => $tmp );
        array_push($PAGE->menu_top, $menu_element);

    }

?>
