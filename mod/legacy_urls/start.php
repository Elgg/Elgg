<?php
/**
 * Provides support for URLs no longer used in Elgg for those who bookmarked or
 * linked to them
 */

elgg_register_event_handler('init', 'system', 'legacy_urls_init');

/**
 * Initialize the plugin
 * @return void
 */
function legacy_urls_init() {
	elgg_register_page_handler('tag', 'legacy_urls_tag_handler');
	elgg_register_page_handler('pg', 'legacy_urls_pg_handler');
	elgg_register_plugin_hook_handler('route', 'blog', 'legacy_urls_blog_forward');
	elgg_register_plugin_hook_handler('route', 'bookmarks', 'legacy_urls_bookmarks_forward');
	elgg_register_plugin_hook_handler('route', 'file', 'legacy_urls_file_forward');
	elgg_register_plugin_hook_handler('route', 'groups', 'legacy_urls_groups_forward');
	elgg_register_plugin_hook_handler('route', 'settings', 'legacy_urls_settings_forward');
	elgg_register_page_handler('forum', 'legacy_urls_forum_handler');
	elgg_register_plugin_hook_handler('route', 'messageboard', 'legacy_urls_messageboard_forward');
}

/**
 * Redirect the requestor to the new URL
 * Checks the plugin setting to determine the course of action:
 * a) Displays an error page with the new URL
 * b) Forwards to the new URL and displays an error message
 * c) Silently forwards to the new URL
 * 
 * @param string $url Relative or absolute URL
 * @return mixed
 */
function legacy_urls_redirect($url) {
	$method = elgg_get_plugin_setting('redirect_method', 'legacy_urls');

	// we only show landing page or queue warning if html generating page
	$viewtype = elgg_get_viewtype();
	if ($viewtype != 'default' && !elgg_does_viewtype_fallback($viewtype)) {
		$method = 'immediate';
	}

	switch ($method) {
		case 'landing':
			$content = elgg_view('legacy_urls/message', array('url' => $url));
			$body = elgg_view_layout('error', array('content' => $content));
			echo elgg_view_page('', $body, 'error');
			return true;
			break;
		case 'immediate_error':
			// drop through after setting error message
			register_error(elgg_echo('changebookmark'));
		case 'immediate':
		default:
			$url = elgg_normalize_url($url);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url");
			exit;
			break;
	}
}

/**
 * Adds query parameters to URL for redirect
 * 
 * @param string $url        The URL
 * @param array  $query_vars Additional query parameters in associate array
 * @return string
 */
function legacy_urls_prepare_url($url, array $query_vars = array()) {
	$params = array();
	// Elgg munges the request in htaccess rules so cannot use $_GET
	$query = parse_url(_elgg_services()->request->server->get('REQUEST_URI'), PHP_URL_QUERY);
	if ($query) {
		parse_str($query, $params);
	}
	$params = array_merge($params, $query_vars);
	if ($params) {
		if (!empty($params['__elgg_uri'])) {
			// on multiple redirects, __elgg_uri is appended to the URL causing infinite loops #8494
			unset($params['__elgg_uri']);
		}
		return elgg_http_add_url_query_elements($url, $params);		
	} else {
		return $url;
	}
}

/**
 * Handle requests for /tag/<tag string>
 *
 * @param array $segments URL segments
 * @return mixed
 */
function legacy_urls_tag_handler($segments) {
	$tag = $segments[0];
	$url = legacy_urls_prepare_url('search', array('q' => $tag));
	return legacy_urls_redirect($url);
}

/**
 * Handle requests for URLs that start with /pg/
 *
 * @param array $segments URL segments
 * @return mixed
 */
function legacy_urls_pg_handler($segments) {
	$url = implode('/', $segments);
	return legacy_urls_redirect(legacy_urls_prepare_url($url));
}

/**
 * Blog forwarder
 * 
 * 1.0-1.7.5
 * Group blogs page: /blog/group:<container_guid>/
 * Group blog view:  /blog/group:<container_guid>/read/<guid>/<title>
 * 1.7.5-pre 1.8
 * Group blogs page: /blog/owner/group:<container_guid>/
 * Group blog view:  /blog/read/<guid>
 *
 * @param $hook   string "route"
 * @param $type   string "blog"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_blog_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");

	// group usernames
	if (preg_match('~/group\:([0-9]+)/~', "/{$page[0]}/{$page[1]}/", $matches)) {
		$guid = $matches[1];
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'group')) {
			if (!empty($page[2])) {
				$url = "blog/view/$page[2]/";
			} else {
				$url = "blog/group/$guid/all";
			}
			// we drop query params because the old group URLs were invalid
			legacy_urls_redirect(legacy_urls_prepare_url($url));
			return false;
		}
	}

	if (empty($page[0])) {
		return;
	}

	if ($page[0] == "read") {
		$url = "blog/view/{$page[1]}/";
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;		
	}

	// user usernames
	$user = get_user_by_username($page[0]);
	if (!$user) {
		return;
	}

	if (empty($page[1])) {
		$page[1] = 'owner';
	}

	switch ($page[1]) {
		case "read":
			$url = "blog/view/{$page[2]}/{$page[3]}";
			break;
		case "archive":
			$url = "blog/archive/{$page[0]}/{$page[2]}/{$page[3]}";
			break;
		case "friends":
			$url = "blog/friends/{$page[0]}";
			break;
		case "new":
			$url = "blog/add/$user->guid";
			break;
		case "owner":
			$url = "blog/owner/{$page[0]}";
			break;
	}

	if (isset($url)) {
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;
	}
}

/**
 * Bookmarks forwarder
 * /bookmarks/group:<group_guid>/
 * /bookmarks/gorup:<group_guid>/read/<guid>/
 * /bookmarks/read/<guid>
 * /bookmarks/<username>[/(items|read|inbox|friends|add|bookmarklet)/<guid>]
 *
 * @param $hook   string "route"
 * @param $type   string "bookmarks"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_bookmarks_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");

	// old group usernames
	if (substr_count($page[0], 'group:')) {
		preg_match('/group\:([0-9]+)/i', $page[0], $matches);
		$guid = $matches[1];
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'group')) {
			if (!empty($page[2])) {
				$url = "bookmarks/view/$page[2]/";
			} else {
				$url = "bookmarks/group/$guid/all";
			}
			// we drop query params because the old group URLs were invalid
			legacy_urls_redirect(legacy_urls_prepare_url($url));
		}
	}

	if ($page[0] == "read") {
		$url = "bookmarks/view/{$page[1]}/";
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;		
	}

	$user = get_user_by_username($page[0]);
	if (!$user) {
		return;
	}

	if (empty($page[1])) {
		$page[1] = 'items';
	}

	switch ($page[1]) {
		case "read":
			$url = "bookmarks/view/{$page[2]}/{$page[3]}";
			break;
		case "inbox":
			$url = "bookmarks/inbox/{$page[0]}";
			break;
		case "friends":
			$url = "bookmarks/friends/{$page[0]}";
			break;
		case "add":
			$url = "bookmarks/add/{$page[0]}";
			break;
		case "items":
			$url = "bookmarks/owner/{$page[0]}";
			break;
		case "bookmarklet":
			$url = "bookmarks/bookmarklet/{$page[0]}";
			break;
	}

	if (isset($url)) {
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;
	}
}

/**
 * File forwarder
 * /file/read/<guid>
 *
 * @param $hook   string "route"
 * @param $type   string "file"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_file_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");

	if ($page[0] == 'read') {
		$url = "file/view/{$page[1]}";
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;
	}
}

/**
 * Groups forwarder
 * /groups/<guid>
 * /groups/forum/<guid>
 *
 * @param $hook   string "route"
 * @param $type   string "groups"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_groups_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");

	if (is_numeric($page[0])) {
		$group = get_entity($page[0]);
		if (elgg_instanceof($group, 'group', '', 'ElggGroup')) {
			legacy_urls_redirect(legacy_urls_prepare_url($group->getURL()));
			return false;
		}
	}

	if ($page[0] == 'forum') {
		$url = "discussion/owner/{$page[1]}";
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;
	}
}

/**
 * User settings forwarder
 * /settings/plugins/
 * 
 * @param $hook   string "route"
 * @param $type   string "settings"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_settings_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");
	
	if ($page[0] == "plugins") {
		if (empty($page[2])) {
			$url = "settings";
			if (!empty($page[1])) {
				$url .= "/user/" . $page[1];
			}
			legacy_urls_redirect(legacy_urls_prepare_url($url));
			return false;
		}
	}
}

/**
 * Group forum forwarder
 * /forum/.*
 *
 * @param array $page URL segments
 * @return mixed
 */
function legacy_urls_forum_handler($page) {
	switch ($page[0]) {
		case 'topic':
			$url = "discussion/view/{$page[1]}/{$page[2]}";
			legacy_urls_redirect(legacy_urls_prepare_url($url));
			return true;
		default:
			return false;
	}
}

/**
 * Messageboard forwarder
 * /messageboard/!(owner|add|group)
 *
 * @param $hook   string "route"
 * @param $type   string "messageboard"
 * @param $result mixed  Old identifier and segments
 * @return mixed
 */
function legacy_urls_messageboard_forward($hook, $type, $result) {

	$page = $result['segments'];

	// easier to work with and no notices
	$page = array_pad($page, 4, "");

	// if the first part is a username, forward to new format
	$new_section_one = array('owner', 'add', 'group');
	if (isset($page[0]) && !in_array($page[0], $new_section_one) && get_user_by_username($page[0])) {
		$url = "messageboard/owner/{$page[0]}";
		legacy_urls_redirect(legacy_urls_prepare_url($url));
		return false;
	}
}
