<?php
/**
 * Elgg htmLawed tag filtering.
 *
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/
 *
 * @package ElgghtmLawed
 */


elgg_register_event_handler('init', 'system', 'htmlawed_init');

/**
 * Initialize the htmlawed plugin
 */
function htmlawed_init() {
	elgg_register_plugin_hook_handler('validate', 'input', \HtmlawedPlugin\ValidateInputHook::KLASS, 1);

	$lib = elgg_get_plugins_path() . "htmlawed/vendors/htmLawed/htmLawed.php";
	elgg_register_library('htmlawed', $lib);
	
	elgg_register_plugin_hook_handler('unit_test', 'system', 'htmlawed_test');
}

/**
 * Runs unit tests for htmlawed
 *
 * @return array
 */
function htmlawed_test($hook, $type, $value, $params) {
    $value[] = dirname(__FILE__) . '/tests/tags.php';
    return $value;
}
