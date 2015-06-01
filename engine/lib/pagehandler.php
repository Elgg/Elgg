<?php
/**
 * Elgg page handler functions
 *
 * @package    Elgg.Core
 * @subpackage Routing
 */

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for the identifier 'blog'
 * For all URLs  http://yoururl/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * Page handlers should return true to indicate that they handled the request.
 * Requests not handled are forwarded to the front page with a reason of 404.
 * Plugins can register for the 'forward', '404' plugin hook. @see forward()
 *
 * @param string $identifier The page type identifier
 * @param string $function   Your function name
 *
 * @return bool Depending on success
 */
function elgg_register_page_handler($identifier, $function) {
	return _elgg_services()->router->registerPageHandler($identifier, $function);
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call elgg_register_page_handler()
 *
 * @param string $identifier The page type identifier
 *
 * @since 1.7.2
 * @return void
 */
function elgg_unregister_page_handler($identifier) {
	_elgg_services()->router->unregisterPageHandler($identifier);
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 * @since 1.9.0
 */
function elgg_gatekeeper() {
	if (!elgg_is_logged_in()) {
		_elgg_services()->session->set('last_forward_from', current_page_url());
		system_message(elgg_echo('loggedinrequired'));
		forward('/login', 'login');
	}
}

/**
 * Alias of elgg_gatekeeper()
 * 
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 */
function gatekeeper() {
	elgg_gatekeeper();
}

/**
 * Used at the top of a page to mark it as admin only.
 *
 * @return void
 * @since 1.9.0
 */
function elgg_admin_gatekeeper() {
	elgg_gatekeeper();

	if (!elgg_is_admin_logged_in()) {
		_elgg_services()->session->set('last_forward_from', current_page_url());
		register_error(elgg_echo('adminrequired'));
		forward('', 'admin');
	}
}

/**
 * Alias of elgg_admin_gatekeeper()
 *
 * Used at the top of a page to mark it as logged in admin or siteadmin only.
 *
 * @return void
 */
function admin_gatekeeper() {
	elgg_admin_gatekeeper();
}

/**
 * May the current user access item(s) on this page? If the page owner is a group,
 * membership, visibility, and logged in status are taken into account.
 *
 * @param bool $forward    If set to true (default), will forward the page;
 *                         if set to false, will return true or false.
 *
 * @param int  $group_guid The group that owns the page. If not set, this
 *                         will be pulled from elgg_get_page_owner_guid().
 *
 * @return bool Will return if $forward is set to false.
 * @since 1.9.0
 */
function elgg_group_gatekeeper($forward = true, $group_guid = null) {
	if (null === $group_guid) {
		$group_guid = elgg_get_page_owner_guid();
	}

	if (!$group_guid) {
		return true;
	}

	// this handles non-groups and invisible groups
	$visibility = \Elgg\GroupItemVisibility::factory($group_guid);

	if (!$visibility->shouldHideItems) {
		return true;
	}
	if ($forward) {
		// only forward to group if user can see it
		$group = get_entity($group_guid);
		$forward_url = $group ? $group->getURL() : '';

		if (!elgg_is_logged_in()) {
			_elgg_services()->session->set('last_forward_from', current_page_url());
			$forward_reason = 'login';
		} else {
			$forward_reason = 'member';
		}

		$msg_keys = array(
			'non_member' => 'membershiprequired',
			'logged_out' => 'loggedinrequired',
			'no_access' => 'noaccess',
		);
		register_error(elgg_echo($msg_keys[$visibility->reasonHidden]));
		forward($forward_url, $forward_reason);
	}

	return false;
}

/**
 * May the current user access item(s) on this page? If the page owner is a group,
 * membership, visibility, and logged in status are taken into account.
 *
 * @param bool $forward         If set to true (default), will forward the page;
 *                              if set to false, will return true or false.
 *
 * @param int  $page_owner_guid The current page owner guid. If not set, this
 *                              will be pulled from elgg_get_page_owner_guid().
 *
 * @return bool Will return if $forward is set to false.
 */
function group_gatekeeper($forward = true, $page_owner_guid = null) {
	return elgg_group_gatekeeper($forward, $page_owner_guid);
}

/**
 * Can the viewer see this entity?
 *
 * Tests if the entity exists and whether the viewer has access to the entity
 * if it does. If the viewer cannot view this entity, it forwards to an
 * appropriate page.
 *
 * @param int    $guid    Entity GUID
 * @param string $type    Optional required entity type
 * @param string $subtype Optional required entity subtype
 * @return void
 * @since 1.9.0
 */
function elgg_entity_gatekeeper($guid, $type = null, $subtype = null) {
	$entity = get_entity($guid);
	if (!$entity) {
		if (!elgg_entity_exists($guid)) {
			// entity doesn't exist
			forward('', '404');
		} elseif (!elgg_is_logged_in()) {
			// entity requires at least a logged in user
			elgg_gatekeeper();
		} else {
			// user is logged in but still does not have access to it
			register_error(elgg_echo('limited_access'));
			forward();
		}		
	}

	if ($type) {
		if (!elgg_instanceof($entity, $type, $subtype)) {
			// entity is of wrong type/subtype
			forward('', '404');
		}
	}
}

/**
 * Require that the current request be an XHR. If not, execution of the current function
 * will end and a 400 response page will be sent.
 *
 * @return void
 * @since 1.12.0
 */
function elgg_ajax_gatekeeper() {
	if (!elgg_is_xhr()) {
		register_error(_elgg_services()->translator->translate('ajax:not_is_xhr'));
		forward(null, '400');
	}
}

/**
 * Front page handler
 * 
 * @return bool
 */
function elgg_front_page_handler() {

	if (elgg_is_logged_in()) {
		forward('activity');
	}

	$title = elgg_echo('content:latest');
	$content = elgg_list_river();
	if (!$content) {
		$content = elgg_echo('river:none');
	}

	$login_box = elgg_view('core/account/login_box');

	$params = array(
			'title' => $title,
			'content' => $content,
			'sidebar' => $login_box
	);
	$body = elgg_view_layout('one_sidebar', $params);
	echo elgg_view_page(null, $body);
	return true;
}

/**
 * Serve an error page
 *
 * This is registered by Elgg for the 'forward', '404' plugin hook. It can
 * registered for other hooks by plugins or called directly to display an
 * error page.
 *
 * @param string $hook   The name of the hook
 * @param string $type   Http error code
 * @param bool   $result The current value of the hook
 * @param array  $params Parameters related to the hook
 * @return void
 */
function elgg_error_page_handler($hook, $type, $result, $params) {
	if (elgg_view_exists("errors/$type")) {
		$title = elgg_echo("error:$type:title");
		if ($title == "error:$type:title") {
			// use default if there is no title for this error type
			$title = elgg_echo("error:default:title");
		}
		
		$content = elgg_view("errors/$type", $params);
	} else {
		$title = elgg_echo("error:default:title");
		$content = elgg_view("errors/default", $params);
	}
	
	$httpCodes = array(
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'407' => 'Proxy Authentication Required',
		'500' => 'Internal Server Error',
		'503' => 'Service Unavailable',
	);
	
	if (isset($httpCodes[$type])) {
		header("HTTP/1.1 $type {$httpCodes[$type]}");
	}

	$body = elgg_view_layout('error', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $body, 'error');
	exit;
}

/**
 * Initializes the page handler/routing system
 *
 * @return void
 * @access private
 */
function _elgg_page_handler_init() {
	elgg_register_page_handler('', 'elgg_front_page_handler');
	// Registered at 600 so that plugins can register at the default 500 and get to run first
	elgg_register_plugin_hook_handler('forward', '400', 'elgg_error_page_handler', 600);
	elgg_register_plugin_hook_handler('forward', '403', 'elgg_error_page_handler', 600);
	elgg_register_plugin_hook_handler('forward', '404', 'elgg_error_page_handler', 600);
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_page_handler_init');
};
