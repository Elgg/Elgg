<?php
// $parameter[0] is an optional array of textbox ids to add to the elements list
// for when this is called explicitly as run('tinymce:include')

    // Language setting

    global $CFG, $USER;
    global $page_owner;

    if (run('userdetails:editor', $USER->ident) == "yes") {

        if (empty($USER->locale) || $USER->locale == 'default') {
            // Userlocale not set, use default
            if (substr($CFG->defaultlocale, 2, 1) == "_")
            {
                $lang = substr($CFG->defaultlocale, 0, 2);
            } else {
                $lang = $CFG->defaultlocale;
            }
        } else {
            // Userlocale set
            if (substr($USER->locale, 2, 1) == "_")
            {
                $lang = substr($USER->locale, 0, 2);
            } else {
                $lang = $USER->locale;
            }
        }

        // Lose the trailing slash
        $url = substr($CFG->wwwroot, 0, -1);

        global $metatags;
        
        // gzip thingy should only assemble plugins we're actually using
        $plugins = 'spellchecker,emotions,contextmenu,preview,style,searchreplace,safari';
        //plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spellchecker',
        
        // in blog weblogs:edit $parameter is an integer.
        // in weblogs:posts:view:individual $parameter is an object.
        // tinymce:include wants an array.
        if (!empty($parameter) && is_array($parameter) && !empty($parameter[0]) && is_array($parameter[0])) {
            $elements = $parameter[0];
        } else {
            $elements = array("new_weblog_post","new_weblog_comment");
        }
        $elementstring = implode(",", $elements);
        
        // auto select spellchecker language based on $lang
        $available_langs = array('English=en','Dutch=nl','German=de',
                                 'Spanish=es','Danish=dk','Swedish=sv',         
                                 'French=fr','Japanese=jp');
        $spell_langs = array();
        $spell_found = false;

        foreach ($available_langs as $slang) {
            $lang_code = substr($slang,strlen($slang)-2,2);

            if ($lang_code == $lang) {
                $spell_langs[] = '+' . $slang;
                $spell_found = true;
            } else {
                $spell_langs[] = $slang;
            }
        }

        $spellcheck_languages = implode(',', $spell_langs);

        // Set default first language if lang not found in spell langs
        if (!$spell_found) {
            $spellcheck_languages = '+' . $spellcheck_languages;
        }

        $metatags .= <<< END
    <script language="javascript" type="text/javascript" src="$url/mod/tinymce/lib/jscripts/tiny_mce/tiny_mce_gzip.js"></script>

    <script language="javascript" type="text/javascript">
    tinyMCE_GZ.init({
        plugins : '$plugins',
        themes : 'advanced',
        language : '$lang',
        disk_cache : true,
        debug : false
    });
    </script>

    <script language="javascript" type="text/javascript">
    tinyMCE.init({
        language : "$lang",
        mode : "exact",
        plugins : "$plugins",
        convert_urls : false,
        relative_urls : false,
        elements : "$elementstring",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,image,undo,redo,link,unlink,code,spellchecker,emotions",
        theme_advanced_buttons2 : "preview,styleprops,search,replace",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_path_location : "bottom",
        plugin_preview_width : "500",
        plugin_preview_height : "600",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]", 
        remove_linebreaks: true,
        theme_advanced_source_editor_width : "400",
        theme_advanced_source_editor_height : "400",
        spellchecker_languages : "$spellcheck_languages",
        document_base_url : "$url",
        fullscreen_settings : {
            theme_advanced_path_location : "top"
        }
        });
    </script>\n
END;
    }
?>
