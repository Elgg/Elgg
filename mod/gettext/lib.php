<?php
global $CFG;
// Gettext internationalisation module
require_once ($CFG->dirroot . 'lib/php-getttext/gettext.inc');
require_once (dirname(__FILE__) . "/lib/library.php");

// Set default translation domain, if not available in config
if (!isset ($CFG->translation_domain)) {
	$CFG->translation_domain = "elgg";
}
// Initialize internationalization
init_i18n();

function gettext_pagesetup() {
}

function gettext_init() {
	global $CFG, $function;

	// Add to runtime
	$function['userdetails:edit:details'][] = $CFG->dirroot . 'mod/gettext/lib/gettext_userdetails_edit_details.php';
	$function['userdetails:init'][] = $CFG->dirroot . 'mod/gettext/lib/gettext_userdetails_actions.php';

}
?>
