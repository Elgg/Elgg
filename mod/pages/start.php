<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

elgg_register_event_handler('init', 'system', 'pages_init');

/**
 * Initialize the pages plugin.
 *
 */
function pages_init() {

	// register a library of helper functions
	elgg_register_library('elgg:pages', __DIR__ . '/lib/pages.php');

	$item = new ElggMenuItem('pages', elgg_echo('pages'), 'pages/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('pages', 'pages_page_handler');

	// Register a url handler
	elgg_register_plugin_hook_handler('entity:url', 'object', 'pages_set_url');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'pages_set_url');
	elgg_register_plugin_hook_handler('extender:url', 'annotation', 'pages_set_revision_url');

	// Extend the main css view
	elgg_extend_view('elgg.css', 'pages/css');

	elgg_define_js('jquery.treeview', [
		'src' => '/mod/pages/vendors/jquery-treeview/jquery.treeview.min.js',
		'exports' => 'jQuery.fn.treeview',
		'deps' => ['jquery'],
	]);
	$css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery.treeview', $css_url);

	elgg_register_plugin_hook_handler('search', 'object:page', 'pages_search_pages');

	// Register for notifications
	elgg_register_notification_event('object', 'page');
	elgg_register_notification_event('object', 'page_top');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page', 'pages_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page_top', 'pages_prepare_notification');

	// add to groups
	add_group_tool_option('pages', elgg_echo('groups:enablepages'), true);
	elgg_extend_view('groups/tool_latest', 'pages/group_module');
	
	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	elgg_set_config('pages', [
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'parent_guid' => 'parent',
		'access_id' => 'access',
		'write_access_id' => 'access',
	]);

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'pages_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');

	elgg_register_plugin_hook_handler('access:collections:write', 'user', 'pages_write_access_options_hook');

	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'pages_icon_url_override');

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'pages_entity_menu_setup');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'pages_ecml_views_hook');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:page', 'Elgg\Values::getTrue');
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:page_top', 'Elgg\Values::getTrue');
	
	// prevent public write access
	elgg_register_plugin_hook_handler('view_vars', 'input/access', 'pages_write_access_vars');
}

/**
 * Dispatcher for pages.
 * URLs take the form of
 *  All pages:        pages/all
 *  User's pages:     pages/owner/<username>
 *  Friends' pages:   pages/friends/<username>
 *  View page:        pages/view/<guid>/<title>
 *  New page:         pages/add/<guid> (container: user, group, parent)
 *  Edit page:        pages/edit/<guid>
 *  History of page:  pages/history/<guid>
 *  Revision of page: pages/revision/<id>
 *  Group pages:      pages/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function pages_page_handler($page) {

	elgg_load_library('elgg:pages');
	
	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			echo elgg_view_resource('pages/owner');
			break;
		case 'friends':
			echo elgg_view_resource('pages/friends');
			break;
		case 'view':
			echo elgg_view_resource('pages/view', [
				'guid' => $page[1],
			]);
			break;
		case 'add':
			echo elgg_view_resource('pages/new', [
				'guid' => $page[1],
			]);
			break;
		case 'edit':
			echo elgg_view_resource('pages/edit', [
				'guid' => $page[1],
			]);
			break;
		case 'group':
			echo elgg_view_resource('pages/owner');
			break;
		case 'history':
			echo elgg_view_resource('pages/history', [
				'guid' => $page[1],
			]);
			break;
		case 'revision':
			echo elgg_view_resource('pages/revision', [
				'id' => $page[1],
			]);
			break;
		case 'all':
			$dir = __DIR__ . "/views/" . elgg_get_viewtype();
			echo elgg_view_resource('pages/all');
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the page url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function pages_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (pages_is_page($entity)) {
		$title = elgg_get_friendly_title($entity->title);
		return "pages/view/$entity->guid/$title";
	}
}

/**
 * Override the page annotation url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function pages_set_revision_url($hook, $type, $url, $params) {
	$annotation = $params['extender'];
	if ($annotation->getSubtype() == 'page') {
		return "pages/revision/$annotation->id";
	}
}

/**
 * Override the default entity icon for pages
 *
 * @return string Relative URL
 */
function pages_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	if (pages_is_page($entity)) {
		switch ($params['size']) {
			case 'topbar':
			case 'tiny':
			case 'small':
				return elgg_get_simplecache_url('pages/pages.gif');
				break;
			default:
				return elgg_get_simplecache_url('pages/pages_lrg.gif');
				break;
		}
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function pages_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "pages/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('pages', elgg_echo('pages'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->pages_enable != "no") {
			$url = "pages/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('pages', elgg_echo('pages:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to pages plugin
 */
function pages_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	elgg_load_library('elgg:pages');
	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'pages') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in()
		&& elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()
		&& ! pages_can_delete_page($entity)) {
		foreach ($return as $index => $item) {
			if ($item->getName() == 'delete') {
				unset($return[$index]);
			}
		}
	}

	$options = [
		'name' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => "pages/history/$entity->guid",
		'priority' => 150,
	];
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Prepare a notification message about a new page
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function pages_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->title;

	$notification->subject = elgg_echo('pages:notify:subject', [$title], $language);
	$notification->body = elgg_echo('pages:notify:body', [
		$owner->name,
		$title,
		$descr,
		$entity->getURL(),
	], $language);
	$notification->summary = elgg_echo('pages:notify:summary', [$entity->title], $language);
	$notification->url = $entity->getURL();
	return $notification;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function pages_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	if (!pages_is_page($params['entity'])) {
		return null;
	}
	$entity = $params['entity'];
	/* @var ElggObject $entity */

	$write_permission = $entity->write_access_id;
	$user = $params['user'];

	if ($write_permission && $user) {
		switch ($write_permission) {
			case ACCESS_PRIVATE:
				// Elgg's default decision is what we want
				return null;
				break;
			case ACCESS_FRIENDS:
				$owner = $entity->getOwnerEntity();
				if (($owner instanceof ElggUser) && $owner->isFriendsWith($user->guid)) {
					return true;
				}
				break;
			default:
				$list = get_access_array($user->guid);
				if (in_array($write_permission, $list)) {
					// user in the access collection
					return true;
				}
				break;
		}
	}
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function pages_container_permission_check($hook, $entity_type, $returnvalue, $params) {
	$container = elgg_extract('container', $params);
	$user = elgg_extract('user', $params);
	$subtype = elgg_extract('subtype', $params);

	// check type/subtype
	if ($entity_type !== 'object' || !in_array($subtype, ['page', 'page_top'])) {
		return null;
	}

	// OK if you can write to the container
	if ($container && $container->canWriteToContainer($user->guid)) {
		return true;
	}

	// look up a page object given via input
	if ($page_guid = get_input('page_guid', 0)) {
		$page = get_entity($page_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$page = get_entity($parent_guid);
	}
	if (!pages_is_page($page)) {
		return null;
	}

	// try the page's container
	$page_container = $page->getContainerEntity();
	if ($page_container && $page_container->canWriteToContainer($user->guid)) {
		return true;
	}

	// I don't understand this but it's old - mrclay
	if (in_array($page->write_access_id, get_access_list())) {
		return true;
	}
}

/**
 * Return views to parse for pages.
 *
 * @param string $hook
 * @param string $entity_type
 * @param array  $return_value
 * @param array  $params
 *
 * @return array
 */
function pages_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/page'] = elgg_echo('item:object:page');
	$return_value['object/page_top'] = elgg_echo('item:object:page_top');

	return $return_value;
}

/**
 * Is the given value a page object?
 *
 * @param mixed $value
 *
 * @return bool
 * @access private
 */
function pages_is_page($value) {
	return ($value instanceof ElggObject) && in_array($value->getSubtype(), ['page', 'page_top']);
}

/**
 * Return options for the write_access_id input
 *
 * @param string $hook
 * @param string $type
 * @param array  $return_value
 * @param array  $params
 *
 * @return array
 */
function pages_write_access_options_hook($hook, $type, $return_value, $params) {
	if (empty($params['input_params']['entity_subtype'])
			|| !in_array($params['input_params']['entity_subtype'], ['page', 'page_top'])) {
		return null;
	}

	if ($params['input_params']['purpose'] === 'write') {
		unset($return_value[ACCESS_PUBLIC]);
		return $return_value;
	}
}


/**
 * Called on view_vars, input/access hook
 * Prevent ACCESS_PUBLIC from ending up as a write access option
 *
 * @param string $hook
 * @param string $type
 * @param array $return
 * @param array $params
 * @return array
 */
function pages_write_access_vars($hook, $type, $return, $params) {
	
	if ($return['name'] != 'write_access_id') {
		return $return;
	}
	
	if ($return['purpose'] != 'write') {
		return $return;
	}
	
	if ($return['value'] != ACCESS_PUBLIC && $return['value'] != ACCESS_DEFAULT) {
		return $return;
	}
	
	$default_access = get_default_access();
	
	if ($return['value'] == ACCESS_PUBLIC || $default_access == ACCESS_PUBLIC) {
		// is the value public, or default which resolves to public?
		// if so we'll set it to logged in, the next most permissible write access level
		$return['value'] = ACCESS_LOGGED_IN;
	}
	
	return $return;
}

/**
 * Search in both top pages and sub pages
 *
 * @param string $hook   the name of the hook
 * @param string $type   the type of the hook
 * @param mixed  $value  the current return value
 * @param array  $params supplied params
 */
function pages_search_pages($hook, $type, $value, $params) {

	if (empty($params) || !is_array($params)) {
		return $value;
	}

	$subtype = elgg_extract("subtype", $params);
	if (empty($subtype) || ($subtype !== "page")) {
		return $value;
	}

	unset($params["subtype"]);
	$params["subtypes"] = ["page_top", "page"];

	// trigger the 'normal' object search as it can handle the added options
	return elgg_trigger_plugin_hook('search', 'object', $params, []);
}
