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
 * @param bool   $forward If set to true (default), will forward the page;
 *                        if set to false, will return true or false.
 * @return bool Will return if $forward is set to false.
 * @since 1.9.0
 */
function elgg_entity_gatekeeper($guid, $type = null, $subtype = null, $forward = true) {
	$entity = get_entity($guid);
	if (!$entity && $forward) {
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
	} else if (!$entity) {
		return false;
	}

	if ($type && !elgg_instanceof($entity, $type, $subtype)) {
		// entity is of wrong type/subtype
		if ($forward) {
			forward('', '404');
		} else {
			return false;
		}
	}

	$hook_type = "{$entity->getType()}:{$entity->getSubtype()}";
	$hook_params = [
		'entity' => $entity,
		'forward' => $forward,
	];
	if (!elgg_trigger_plugin_hook('gatekeeper', $hook_type, $hook_params, true)) {
		if ($forward) {
			forward('', '403');
		} else {
			return false;
		}
	}

	return true;
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
	return elgg_ok_response(elgg_view_resource('index'));
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
 * @deprecated 2.3
 */
function elgg_error_page_handler($hook, $type, $result, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Error pages are drawn by resource views without "forward" hook.', '2.3');
	
	// This draws an error page, and sometimes there's another 40* forward() call made during that
	// process (usually due to the pagesetup event). We want to allow the 2nd call to pass through,
	// but draw the appropriate page for the first call.
	
	static $vars;
	if ($vars === null) {
		// keep first vars for error page
		$vars = [
			'type' => $type,
			'params' => $params,
		];
	}

	static $calls = 0;
	$calls++;
	if ($calls < 3) {
		echo elgg_view_resource('error', $vars);
		exit;
	}

	// uh oh, may be infinite loop
	register_error(elgg_echo('error:404:content'));
	header('Location: ' . elgg_get_site_url());
	exit;
}

/**
 * Prepares a successful response to be returned by a page or an action handler
 *
 * @param mixed  $content     Response content
 *                            In page handlers, response content should contain an HTML string
 *                            In action handlers, response content can contain either a JSON string or an array of data
 * @param string $message     System message visible to the client
 *                            Can be used by handlers to display a system message
 * @param string $forward_url Forward URL
 *                            Can be used by handlers to redirect the client on non-ajax requests
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response (defaults to 200)
 * @return \Elgg\Http\OkResponse
 */
function elgg_ok_response($content = '', $message = '', $forward_url = null, $status_code = ELGG_HTTP_OK) {
	if ($message) {
		system_message($message);
	}
	return new \Elgg\Http\OkResponse($content, $status_code, $forward_url);
	
}

/**
 * Prepare an error response to be returned by a page or an action handler
 *
 * @param string $error       Error message
 *                            Can be used by handlers to display an error message
 *                            For certain requests this error message will also be used as the response body
 * @param string $forward_url URL to redirect the client to
 *                            Can be used by handlers to redirect the client on non-ajax requests
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response
 *                            For BC reasons and due to the logic in the client-side AJAX API,
 *                            this defaults to 200. Note that the Router and AJAX API will
 *                            treat these responses as error in spite of the HTTP code assigned
 * @return \Elgg\Http\ErrorResponse
 */
function elgg_error_response($error = '', $forward_url = REFERRER, $status_code = ELGG_HTTP_OK) {
	if ($error) {
		register_error($error);
	}
	return new \Elgg\Http\ErrorResponse($error, $status_code, $forward_url);
}

/**
 * Prepare a silent redirect response to be returned by a page or an action handler
 *
 * @param string $forward_url Redirection URL
 *                            Relative or absolute URL to redirect the client to
 * @param int    $status_code HTTP status code
 *                            Status code of the HTTP response
 *                            Note that the Router and AJAX API will treat these responses
 *                            as redirection in spite of the HTTP code assigned
 *                            Note that non-redirection HTTP codes will throw an exception
 * @return \Elgg\Http\RedirectResponse
 * @throws \InvalidArgumentException
 */
function elgg_redirect_response($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {
	return new Elgg\Http\RedirectResponse($forward_url, $status_code);
}

/**
 * Initializes the page handler/routing system
 *
 * @return void
 * @access private
 */
function _elgg_page_handler_init() {
	elgg_register_page_handler('', 'elgg_front_page_handler');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_page_handler_init');
};
