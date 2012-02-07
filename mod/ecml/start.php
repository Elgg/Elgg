<?php
/**
 * Provides the ECML service.
 *
 * @package ECML
 */

// be sure to run after other plugins
elgg_register_event_handler('init', 'system', 'ecml_init', 9999);

function ecml_init() {

	// get list of views to process for ECML
	// entries should be of the form 'view/name' => 'View description'
	$view = array(
		'output/longtext' => elgg_echo('ecml:view:output_longtext'),
	);
	$views = elgg_trigger_plugin_hook('get_views', 'ecml', null, array());

	foreach ($views as $view => $desc) {
		elgg_register_plugin_hook_handler('view', $view, 'ecml_process_view');
	}
}

/**
 * Processes a view output for ECML tags
 *
 * @param string $hook   The name of the hook
 * @param string $name   The name of the view
 * @param string $value  The value of the view
 * @param array  $params The parameters for the view
 * @return string
 */
function ecml_process_view($hook, $name, $value, $params) {
	$markup_processor = new ElggMarkup();
	return $markup_processor->process($value);
}
