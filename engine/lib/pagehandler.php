<?php
/**
 * Elgg page handler functions
 *
 * @package    Elgg.Core
 * @subpackage Routing
 */

/**
 * Register a new route
 *
 * Route paths can contain wildcard segments, i.e. /blog/owner/{username}
 * To make a certain wildcard segment optional, add ? to its name,
 * i.e. /blog/owner/{username?}
 *
 * Wildcard requirements for common named variables such as 'guid' and 'username'
 * will be set automatically.
 *
 * @warning If you are registering a route in the path of a route registered by
 *          deprecated {@link elgg_register_page_handler}, your registration must
 *          preceed the call to elgg_register_page_handler() in the boot sequence.
 *
 * @param string $name   Unique route name
 *                       This name can later be used to generate route URLs
 * @param array  $params Route parameters
 *                       - path : path of the route
 *                       - resource : name of the resource view
 *                       - defaults : default values of wildcard segments
 *                       - requirements : regex patterns for wildcard segment requirements
 *                       - methods : HTTP methods
 *
 * @return \Elgg\Router\Route
 */
function elgg_register_route($name, array $params = []) {
	return _elgg_services()->router->registerRoute($name, $params);
}

/**
 * Unregister a route by its name
 *
 * @param string $name Name of the route
 *
 * @return void
 */
function elgg_unregister_route($name) {
	_elgg_services()->router->unregisterRoute($name);
}

/**
 * Generate a URL for named route
 *
 * @param string $name       Route name
 * @param array  $parameters Parameters
 *
 * @return string
 */
function elgg_generate_url($name, array $parameters = []) {
	return _elgg_services()->router->generateUrl($name, $parameters);
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 * @throws \Elgg\GatekeeperException
 * @since 1.9.0
 */
function elgg_gatekeeper() {
	if (!elgg_is_logged_in()) {
		_elgg_services()->redirects->setLastForwardFrom();

		$msg = elgg_echo('loggedinrequired');
		throw new \Elgg\GatekeeperException($msg);
	}
}

/**
 * Used at the top of a page to mark it as admin only.
 *
 * @return void
 * @throws \Elgg\GatekeeperException
 * @since 1.9.0
 */
function elgg_admin_gatekeeper() {
	elgg_gatekeeper();

	if (!elgg_is_admin_logged_in()) {
		_elgg_services()->redirects->setLastForwardFrom();

		$msg = elgg_echo('adminrequired');
		throw new \Elgg\GatekeeperException($msg);
	}
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
 * @throws InvalidParameterException
 * @throws SecurityException
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
			_elgg_services()->redirects->setLastForwardFrom();
			$forward_reason = 'login';
		} else {
			$forward_reason = 'member';
		}

		$msg_keys = [
			'non_member' => 'membershiprequired',
			'logged_out' => 'loggedinrequired',
			'no_access' => 'noaccess',
		];
		register_error(elgg_echo($msg_keys[$visibility->reasonHidden]));
		forward($forward_url, $forward_reason);
	}

	return false;
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
 *
 * @return bool Will return if $forward is set to false.
 * @throws \Elgg\BadRequestException
 * @throws \Elgg\EntityNotFoundException
 * @throws \Elgg\EntityPermissionsException
 * @throws \Elgg\GatekeeperException
 * @since 1.9.0
 */
function elgg_entity_gatekeeper($guid, $type = null, $subtype = null, $forward = true) {
	$entity = get_entity($guid);
	if (!$entity && $forward) {
		if (!elgg_entity_exists($guid)) {
			// entity doesn't exist
			throw new \Elgg\EntityNotFoundException();
		} else if (!elgg_is_logged_in()) {
			// entity requires at least a logged in user
			elgg_gatekeeper();
		} else {
			// user is logged in but still does not have access to it
			$msg = elgg_echo('limited_access');
			throw new \Elgg\GatekeeperException($msg);
		}
	} else if (!$entity) {
		return false;
	}

	if ($type && !elgg_instanceof($entity, $type, $subtype)) {
		// entity is of wrong type/subtype
		if ($forward) {
			throw new \Elgg\BadRequestException();
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
			throw new \Elgg\EntityPermissionsException();
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
 * @throws \Elgg\BadRequestException
 * @since 1.12.0
 */
function elgg_ajax_gatekeeper() {
	if (!elgg_is_xhr()) {
		$msg = elgg_echo('ajax:not_is_xhr');
		throw new \Elgg\BadRequestException($msg);
	}
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
 *
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
 *
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
 *
 * @return \Elgg\Http\RedirectResponse
 * @throws \InvalidArgumentException
 */
function elgg_redirect_response($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {
	return new Elgg\Http\RedirectResponse($forward_url, $status_code);
}


/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {

};
