<?php
/**
 * Parameter input functions.
 * This file contains functions for getting input from get/post variables.
 *
 * @package Elgg.Core
 * @subpackage Input
 */

/**
 * Get some input from variables passed submitted through GET or POST.
 *
 * If using any data obtained from get_input() in a web page, please be aware that
 * it is a possible vector for a reflected XSS attack. If you are expecting an
 * integer, cast it to an int. If it is a string, escape quotes.
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 * because of the filtering done in htmlawed from the filter_tags call.
 * @todo Is this ^ still true?
 *
 * @param string $variable      The variable name we want.
 * @param mixed  $default       A default value for the variable if it is not found.
 * @param bool   $filter_result If true, then the result is filtered for bad tags.
 *
 * @return mixed
 */
function get_input($variable, $default = null, $filter_result = true) {
	return _elgg_services()->input->get($variable, $default, $filter_result);
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string          $variable The name of the variable
 * @param string|string[] $value    The value of the variable
 *
 * @return void
 */
function set_input($variable, $value) {
	_elgg_services()->input->set($variable, $value);
}

/**
 * Filter tags from a given string based on registered hooks.
 *
 * @param mixed $var Anything that does not include an object (strings, ints, arrays)
 *					 This includes multi-dimensional arrays.
 *
 * @return mixed The filtered result - everything will be strings
 */
function filter_tags($var) {
	return elgg_trigger_plugin_hook('validate', 'input', null, $var);
}

/**
 * Returns the current page's complete URL.
 *
 * It uses the configured site URL for the hostname rather than depending on
 * what the server uses to populate $_SERVER.
 *
 * @return string The current page URL.
 */
function current_page_url() {
	$url = parse_url(elgg_get_site_url());

	$page = $url['scheme'] . "://" . $url['host'];

	if (isset($url['port']) && $url['port']) {
		$page .= ":" . $url['port'];
	}

	$page = trim($page, "/");

	$page .= _elgg_services()->request->getRequestUri();

	return $page;
}

/**
 * Validates an email address.
 *
 * @param string $address Email address.
 *
 * @return bool
 */
function is_email_address($address) {
	return filter_var($address, FILTER_VALIDATE_EMAIL) === $address;
}

/**
 * Save form submission data (all GET and POST vars) into a session cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string $form_name Name of the sticky form
 *
 * @return void
 * @since 1.8.0
 */
function elgg_make_sticky_form($form_name) {
	_elgg_services()->stickyForms->makeStickyForm($form_name);
}

/**
 * Remove form submission data from the session
 *
 * Call this if validation is successful in the action handler or
 * when they sticky values have been used to repopulate the form
 * after a validation error.
 *
 * @param string $form_name Form namespace
 *
 * @return void
 * @since 1.8.0
 */
function elgg_clear_sticky_form($form_name) {
	_elgg_services()->stickyForms->clearStickyForm($form_name);
}

/**
 * Does form submission data exist for this form?
 *
 * @param string $form_name Form namespace
 *
 * @return boolean
 * @since 1.8.0
 */
function elgg_is_sticky_form($form_name) {
	return _elgg_services()->stickyForms->isStickyForm($form_name);
}

/**
 * Get a specific value from cached form submission data
 *
 * @param string  $form_name     The name of the form
 * @param string  $variable      The name of the variable
 * @param mixed   $default       Default value if the variable does not exist in sticky cache
 * @param boolean $filter_result Filter for bad input if true
 *
 * @return mixed
 *
 * @todo should this filter the default value?
 * @since 1.8.0
 */
function elgg_get_sticky_value($form_name, $variable = '', $default = null, $filter_result = true) {
	return _elgg_services()->stickyForms->getStickyValue($form_name, $variable, $default, $filter_result);
}

/**
 * Get all submission data cached for a form
 *
 * @param string $form_name     The name of the form
 * @param bool   $filter_result Filter for bad input if true
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_sticky_values($form_name, $filter_result = true) {
	return _elgg_services()->stickyForms->getStickyValues($form_name, $filter_result);
}

/**
 * Remove one value of form submission data from the session
 *
 * @param string $form_name The name of the form
 * @param string $variable  The name of the variable to clear
 *
 * @return void
 * @since 1.8.0
 */
function elgg_clear_sticky_value($form_name, $variable) {
	_elgg_services()->stickyForms->clearStickyValue($form_name, $variable);
}

/**
 * Page handler for autocomplete endpoint.
 *
 * @todo split this into functions/objects, this is way too big
 *
 * /livesearch?q=<query>
 *
 * Other options include:
 *     match_on	   string all or array(groups|users|friends)
 *     match_owner int    0/1
 *     limit       int    default is 10
 *     name        string default "members"
 *
 * @param array $page
 * @return string JSON string is returned and then exit
 * @access private
 */
function input_livesearch_page_handler($page) {
	$dbprefix = elgg_get_config('dbprefix');

	// only return results to logged in users.
	if (!$user = elgg_get_logged_in_user_entity()) {
		exit;
	}

	if (!$q = get_input('term', get_input('q'))) {
		exit;
	}

	$input_name = get_input('name', 'members');

	$q = sanitise_string($q);

	// replace mysql vars with escaped strings
	$q = str_replace(['_', '%'], ['\_', '\%'], $q);

	$match_on = get_input('match_on', 'all');

	if (!is_array($match_on)) {
		$match_on = [$match_on];
	}

	// all = users and groups
	if (in_array('all', $match_on)) {
		$match_on = ['users', 'groups'];
	}

	$owner_guid = ELGG_ENTITIES_ANY_VALUE;
	if (get_input('match_owner', false)) {
		$owner_guid = $user->getGUID();
	}

	$limit = sanitise_int(get_input('limit', elgg_get_config('default_limit')));

	// grab a list of entities and send them in json.
	$results = [];
	foreach ($match_on as $match_type) {
		switch ($match_type) {
			case 'users':
				$options = [
					'type' => 'user',
					'limit' => $limit,
					'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
					'wheres' => [
						"ue.banned = 'no'",
						"(ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')"
					]
				];
				
				$entities = elgg_get_entities($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						if (in_array('groups', $match_on)) {
							$value = $entity->guid;
						} else {
							$value = $entity->username;
						}

						$output = elgg_view_list_item($entity, [
							'use_hover' => false,
							'use_link' => false,
							'class' => 'elgg-autocomplete-item',
							'title' => $entity->name, // Default title would be a link
						]);

						$icon = elgg_view_entity_icon($entity, 'tiny', [
							'use_hover' => false,
						]);

						$result = [
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $value,
							'icon' => $icon,
							'url' => $entity->getURL(),
							'html' => elgg_view('input/userpicker/item', [
								'entity' => $entity,
								'input_name' => $input_name,
							]),
						];
						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			case 'groups':
				// don't return results if groups aren't enabled.
				if (!elgg_is_active_plugin('groups')) {
					continue;
				}
				
				$options = [
					'type' => 'group',
					'limit' => $limit,
					'owner_guid' => $owner_guid,
					'joins' => ["JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"],
					'wheres' => [
						"(ge.name LIKE '$q%' OR ge.name LIKE '% $q%' OR ge.description LIKE '% $q%')"
					]
				];
				
				$entities = elgg_get_entities($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						$output = elgg_view_list_item($entity, [
							'use_hover' => false,
							'class' => 'elgg-autocomplete-item',
							'full_view' => false,
							'href' => false,
							'title' => $entity->name, // Default title would be a link
						]);

						$icon = elgg_view_entity_icon($entity, 'tiny', [
							'use_hover' => false,
						]);

						$result = [
							'type' => 'group',
							'name' => $entity->name,
							'desc' => strip_tags($entity->description),
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $entity->guid,
							'icon' => $icon,
							'url' => $entity->getURL(),
						];

						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			case 'friends':
				$options = [
					'type' => 'user',
					'limit' => $limit,
					'relationship' => 'friend',
					'relationship_guid' => $user->getGUID(),
					'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
					'wheres' => [
						"ue.banned = 'no'",
						"(ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')"
					]
				];
				
				$entities = elgg_get_entities_from_relationship($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						$output = elgg_view_list_item($entity, [
							'use_hover' => false,
							'use_link' => false,
							'class' => 'elgg-autocomplete-item',
							'title' => $entity->name, // Default title would be a link
						]);

						$icon = elgg_view_entity_icon($entity, 'tiny', [
							'use_hover' => false,
						]);

						$result = [
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $entity->username,
							'icon' => $icon,
							'url' => $entity->getURL(),
							'html' => elgg_view('input/userpicker/item', [
								'entity' => $entity,
								'input_name' => $input_name,
							]),
						];
						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			default:
				header("HTTP/1.0 400 Bad Request", true);
				echo "livesearch: unknown match_on of $match_type";
				exit;
				break;
		}
	}

	ksort($results);
	header("Content-Type: application/json;charset=utf-8");
	echo json_encode(array_values($results));
	exit;
}

/**
 * htmLawed filtering of data
 *
 * Called on the 'validate', 'input' plugin hook
 *
 * htmLawed's $config argument is filtered by the [config, htmlawed] hook.
 * htmLawed's $spec argument is filtered by the [spec, htmlawed] hook.
 *
 * For information on these arguments, see
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
 *
 * @param string $hook   Hook name
 * @param string $type   The type of hook
 * @param mixed  $result Data to filter
 * @param array  $params Not used
 *
 * @return mixed
 */
function _elgg_htmlawed_filter_tags($hook, $type, $result, $params = null) {
	$var = $result;

	$config = [
		// seems to handle about everything we need.
		'safe' => true,

		// remove comments/CDATA instead of converting to text
		'comment' => 1,
		'cdata' => 1,

		'deny_attribute' => 'class, on*',
		'hook_tag' => '_elgg_htmlawed_tag_post_processor',

		'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
		// apparent this doesn't work.
		// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
	];

	// add nofollow to all links on output
	if (!elgg_in_context('input')) {
		$config['anti_link_spam'] = ['/./', ''];
	}

	$config = elgg_trigger_plugin_hook('config', 'htmlawed', null, $config);
	$spec = elgg_trigger_plugin_hook('spec', 'htmlawed', null, '');

	if (!is_array($var)) {
		return htmLawed($var, $config, $spec);
	} else {
		array_walk_recursive($var, '_elgg_htmLawedArray', [$config, $spec]);
		return $var;
	}
}

// @codingStandardsIgnoreStart
/**
 * wrapper function for htmlawed for handling arrays
 */
function _elgg_htmLawedArray(&$v, $k, $config_spec) {
	list ($config, $spec) = $config_spec;
	$v = htmLawed($v, $config, $spec);
}
// @codingStandardsIgnoreEnd

/**
 * Post processor for tags in htmlawed
 *
 * This runs after htmlawed has filtered. It runs for each tag and filters out
 * style attributes we don't want.
 *
 * This function triggers the 'allowed_styles', 'htmlawed' plugin hook.
 *
 * @param string $element    The tag element name
 * @param array  $attributes An array of attributes
 * @return string
 */
function _elgg_htmlawed_tag_post_processor($element, $attributes = false) {
	if ($attributes === false) {
		// This is a closing tag. Prevent further processing to avoid inserting a duplicate tag
		return "</${element}>";
	}

	// this list should be coordinated with the WYSIWYG editor used (tinymce, ckeditor, etc.)
	$allowed_styles = [
		'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
		'font-weight', 'font-style', 'border', 'border-top', 'background-color',
		'border-bottom', 'border-left', 'border-right',
		'margin', 'margin-top', 'margin-bottom', 'margin-left',
		'margin-right',	'padding', 'float', 'text-decoration'
	];

	$params = ['tag' => $element];
	$allowed_styles = elgg_trigger_plugin_hook('allowed_styles', 'htmlawed', $params, $allowed_styles);

	// must return something.
	$string = '';

	foreach ($attributes as $attr => $value) {
		if ($attr == 'style') {
			$styles = explode(';', $value);

			$style_str = '';
			foreach ($styles as $style) {
				if (!trim($style)) {
					continue;
				}
				list($style_attr, $style_value) = explode(':', trim($style));
				$style_attr = trim($style_attr);
				$style_value = trim($style_value);

				if (in_array($style_attr, $allowed_styles)) {
					$style_str .= "$style_attr: $style_value; ";
				}
			}

			if ($style_str) {
				$style_str = trim($style_str);
				$string .= " style=\"$style_str\"";
			}
		} else {
			$string .= " $attr=\"$value\"";
		}
	}

	// Some WYSIWYG editors do not like tags like <p > so only add a space if needed.
	if ($string = trim($string)) {
		$string = " $string";
	}

	$r = "<$element$string>";
	return $r;
}

/**
 * Runs unit tests for htmlawed
 *
 * @param string   $hook   "unit_test"
 * @param string   $type   "system"
 * @param string[] $value  Test files
 * @param array    $params Hook params
 *
 * @return array
 */
function _elgg_htmlawed_test($hook, $type, $value, $params) {
	$value[] = dirname(__DIR__) . '/tests/ElggHtmLawedTest.php';
	return $value;
}

/**
 * Disable the autocomplete feature on password fields
 *
 * @param string $hook         'view_vars'
 * @param string $type         'input/password'
 * @param array  $return_value the current view vars
 * @param array  $params       supplied params
 *
 * @return void|array
 */
function _elgg_disable_password_autocomplete($hook, $type, $return_value, $params) {
	
	if (!elgg_get_config('security_disable_password_autocomplete')) {
		return;
	}
	
	$return_value['autocomplete'] = 'off';
	
	return $return_value;
}

/**
 * Initialize the input library
 *
 * @return void
 * @access private
 */
function _elgg_input_init() {
	// register an endpoint for live search / autocomplete.
	elgg_register_page_handler('livesearch', 'input_livesearch_page_handler');

	elgg_register_plugin_hook_handler('validate', 'input', '_elgg_htmlawed_filter_tags', 1);

	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_htmlawed_test');
	
	elgg_register_plugin_hook_handler('view_vars', 'input/password', '_elgg_disable_password_autocomplete');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_input_init');
};
