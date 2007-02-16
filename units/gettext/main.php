<?php

    global $CFG;

    // Gettext internationalisation module
	require($CFG->dirroot.'lib/php-getttext/gettext.inc');
    require_once(dirname(__FILE__)."/library.php");

    // Set default translation domain, if not available in config
    if (!isset($CFG->translation_domain))
    {
        $CFG->translation_domain = "elgg";
    }
    // Initialize internationalization
    init_i18n();

    // Add to runtime
    $function['userdetails:edit:details'][] = $CFG->dirroot . 'units/gettext/gettext_userdetails_edit_details.php';
    $function['userdetails:init'][] = $CFG->dirroot . 'units/gettext/gettext_userdetails_actions.php';
?>
