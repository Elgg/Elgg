<?php
/**
 * Provides the ECML service to plugins.
 *
 * @package ECML
 *
 * @todo
 *	Granular access to keywords based upon view.
 *	Update docs / help
 * 	Check for SQL injection problems.
 * 	Check entity keyword views against fullview.  Force to FALSE?
 */

/**
 * Init ECML
 */
function ecml_init() {
	require_once(dirname(__FILE__) . '/ecml_functions.php');
	global $CONFIG;

	define('ECML_ATTR_SEPARATOR', ' ');
	define('ECML_ATTR_OPERATOR', '=');

	// find alphanumerics (keywords) possibly followed by everything that is not a ] (args) and all surrounded by [ ]s
	define('ECML_KEYWORD_REGEX', '/\[([a-z0-9\.]+)([^\]]+)?\]/');

	// help page
	register_page_handler('ecml', 'ecml_help_page_handler');

	// admin access page
	register_page_handler('ecml_admin', 'ecml_admin_page_handler');

	// ecml validator for embed
	register_page_handler('ecml_generate', 'ecml_generate_page_handler');

	// CSS for admin access
	elgg_extend_view('css/screen', 'ecml/admin/css');

	// admin action to save permissions
	elgg_register_action('settings/ecml/save', dirname(__FILE__) . '/actions/save_permissions.php', 'admin');

	// show ECML-enabled icon on free-text input areas
	//elgg_extend_view('input/longtext',  'ecml/input_ext', 0);
	//elgg_extend_view('input/plaintext', 'ecml/input_ext');
	//elgg_extend_view('input/text', 'ecml/input_ext');

	// add parsing for core views.
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'ecml_views_hook');

	// get register the views we want to parse for ecml
	// @todo will need to do profiling to see if it would be faster
	// to foreach through this list and register to specific views or
	// do the check in a single plugin hook.
	// Wants array('view_name' => 'Short Description')
	$CONFIG->ecml_parse_views = elgg_trigger_plugin_hook('get_views', 'ecml', NULL, array());

	foreach ($CONFIG->ecml_parse_views as $view => $desc) {
		elgg_register_plugin_hook_handler('view', $view, 'ecml_parse_view');
	}

	// provide a few built-in ecml keywords.
	// @todo could pull this out into an array here to save an API call.
	elgg_register_plugin_hook_handler('get_keywords', 'ecml', 'ecml_keyword_hook');

	// grab the list of keywords and their views from plugins
	$CONFIG->ecml_keywords = elgg_trigger_plugin_hook('get_keywords', 'ecml', NULL, array());

	// grab permissions for specific views/contexts
	// this is a black list.
	// it's more efficient to use this as a blacklist
	// but probably makes more sense from a UI perspective as a whitelist.
	// uses [views][view_name] = array(keywords, not, allowed)
	$CONFIG->ecml_permissions = unserialize(get_plugin_setting('ecml_permissions', 'ecml'));

	// 3rd party media embed section
	elgg_register_plugin_hook_handler('embed_get_sections', 'all', 'ecml_embed_web_services_hook');

	// remove ecml when stripping tags
	elgg_register_plugin_hook_handler('format', 'strip_tags', 'ecml_strip_tags');
}

/**
 * Display a help page for valid ECML keywords on this page.
 *
 * @param array $page
 */
function ecml_help_page_handler($page) {
	if (!isset($page[0]) || empty($page[0])) {
		$content = elgg_view('ecml/help');
		$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));
		echo elgg_view_page(elgg_echo('ecml:help'), $body);
	} else {
		// asking for detailed help about a keyword
		$keyword = $page[0];
		$content = elgg_view('ecml/keyword_help', array('keyword' => $keyword));

		if (get_input('ajax', FALSE)) {
			echo $content;
			exit;
		} else {
			$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));
			echo elgg_view_page(elgg_echo('ecml:help'), $body);
		}
	}

	return TRUE;
}

/**
 * Generate ECML given a URL or embed link and service.
 * Doesn't check if the resource actually exists.
 * Outputs JSON.
 *
 * @param unknown_type $page
 */
function ecml_generate_page_handler($page) {
	$service = trim(get_input('service'));
	$resource = trim(get_input('resource'));

	// if standard ECML is passed, guess the service from that instead
	// only support one.
	if (elgg_substr($resource, 0, 1) == '[') {
		if ($keywords = ecml_extract_keywords($resource)) {
			$keyword = $keywords[0]['keyword'];
			$ecml_info = ecml_get_keyword_info($keyword);
			$html = ecml_parse_string($resource);

			echo json_encode(array(
				'status' => 'success',
				'ecml' => $resource,
				'html' => $html
			));

			exit;
		}
	}

	if (!$service || !$resource) {
		echo json_encode(array(
			'status' => 'error',
			'message' => elgg_echo('ecml:embed:invalid_web_service_keyword')
		));

		exit;
	}

	$ecml_info = ecml_get_keyword_info($service);

	if ($ecml_info) {
		// don't allow embedding for restricted.
		if (isset($ecml_info['restricted'])) {
			$result = array(
				'status' => 'error',
				'message' => elgg_echo('ecml:embed:cannot_embed'),
			);
		} else {
			// @todo pull this out into a function.  allow optional arguments.
			$ecml = "[$service " . sprintf($ecml_info['embed_format'], $resource) . ']';
			$html = ecml_parse_string($ecml, NULL);
			$result = array(
				'status' => 'success',
				'ecml' => $ecml,
				'html' => $html
			);
		}
	} else {
		$result = array(
			'status' => 'error',
			'message' => elgg_echo('ecml:embed:invalid_web_service_keyword')
		);
	}

	echo json_encode($result);
	exit;
}

/**
 * Display a admin area for ECML
 *
 * @param array $page
 */
function ecml_admin_page_handler($page) {
	admin_gatekeeper();
	elgg_set_context('admin');
	$content = elgg_view('ecml/admin/ecml_admin');
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));
	echo elgg_view_page(elgg_echo('ecml:admin'), $body);
}

/**
 * Parses a registered view / context for supported keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 * @return string
 */
function ecml_parse_view($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	return ecml_parse_string($return_value, $params['view']);
}


/**
 * Register default keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function ecml_keyword_hook($hook, $type, $value, $params) {
	// I keep going back and forth about entity and view. They're powerful, but
	// a great way to let a site get hacked if the admin doesn't lock them down.
	$keywords = array(
		'youtube' => array('params' => array('src', 'width', 'height'), 'embed_format' => 'src="%s"'),
		'slideshare' => array('params' => array('id', 'width', 'height'), 'embed_format' => 'id="%s"'),
		'vimeo' => array('params' => array('src', 'width', 'height'), 'embed_format' => 'src="%s"'),
		'googlemaps' => array('params' => array('src', 'width', 'height'), 'embed_format' => 'src="%s"'),
		//'scribd'
		'blip.tv' => array('params' => array('width', 'height'), 'embed_format' => '%s'),
		'dailymotion' => array('params' => array('src', 'width', 'height'), 'embed_format' => 'src="%s"'),
		'livevideo' => array('params' => array('src', 'width', 'height'), 'embed_format' => 'src="%s"'),
		'redlasso' => array('params' => array('id', 'width', 'height'), 'embed_format' => 'id="%s"'),
	);

	foreach ($keywords as $keyword => $info) {
		$value[$keyword] = array(
			'name' => elgg_echo("ecml:keywords:$keyword"),
			'view' => "ecml/keywords/$keyword",
			'description' => elgg_echo("ecml:keywords:$keyword:desc"),
			'usage' => elgg_echo("ecml:keywords:$keyword:usage"),
			'type' => 'web_service',
			'params' => $info['params'],
			'embed_format' => $info['embed_format']
		);
	}

	// default entity keyword
	$value['entity'] = array(
		'name' => elgg_echo('ecml:keywords:entity'),
		'view' => "ecml/keywords/entity",
		'description' => elgg_echo("ecml:keywords:entity:desc"),
		'usage' => elgg_echo("ecml:keywords:entity:usage")
	);

	return $value;
}

/**
 * Register default views to parse
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function ecml_views_hook($hook, $type, $value, $params) {
	$value['annotation/generic_comment'] = elgg_echo('ecml:views:annotation_generic_comment');

	return $value;
}

/**
 * Show the special Web Services embed section.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function ecml_embed_web_services_hook($hook, $type, $value, $params) {
	// we're using a view override for this section's content
	// so only need to pass the name.
	$value['web_services'] = array(
		'name' => elgg_echo('ecml:embed:web_services')
	);

	return $value;
}

/**
 * Remove ecml code for elgg_strip_tags()
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function ecml_strip_tags($hook, $type, $value, $params) {
	return preg_replace(ECML_KEYWORD_REGEX, '', $value);
}

// be sure to run after other plugins
elgg_register_event_handler('init', 'system', 'ecml_init', 9999);