<?php
/**
 * Elgg Pages
 */

require_once(dirname(__FILE__) . '/lib/pages.php');

/**
 * Initialize the pages plugin
 *
 * @return void
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
	elgg_register_plugin_hook_handler('extender:url', 'annotation', 'pages_set_revision_url');

	// Extend the main css view
	elgg_extend_view('elgg.css', 'pages/css');

	// Register for notifications
	elgg_register_notification_event('object', 'page');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page', 'pages_prepare_notification');

	// add to groups
	add_group_tool_option('pages', elgg_echo('groups:enablepages'), true);
	elgg_extend_view('groups/tool_latest', 'pages/group_module');
	
	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	elgg_set_config('pages', [
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'parent_guid' => 'pages/parent',
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
 * @param array $page URL segments
 *
 * @return bool
 */
function pages_page_handler($page) {

	elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');

	switch (elgg_extract(0, $page, 'all')) {
		case 'owner':
			echo elgg_view_resource('pages/owner');
			break;
		case 'friends':
			echo elgg_view_resource('pages/friends');
			break;
		case 'view':
			echo elgg_view_resource('pages/view', [
				'guid' => (int) elgg_extract(1, $page),
			]);
			break;
		case 'add':
			echo elgg_view_resource('pages/new', [
				'guid' => (int) elgg_extract(1, $page),
			]);
			break;
		case 'edit':
			echo elgg_view_resource('pages/edit', [
				'guid' => (int) elgg_extract(1, $page),
			]);
			break;
		case 'group':
			echo elgg_view_resource('pages/owner');
			break;
		case 'history':
			echo elgg_view_resource('pages/history', [
				'guid' => (int) elgg_extract(1, $page),
			]);
			break;
		case 'revision':
			echo elgg_view_resource('pages/revision', [
				'id' => (int) elgg_extract(1, $page),
			]);
			break;
		case 'all':
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
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    current return value
 * @param array  $params supplied params
 *
 * @return void|string
 */
function pages_set_url($hook, $type, $url, $params) {
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	$title = elgg_get_friendly_title($entity->getDisplayName());
	return "pages/view/{$entity->guid}/{$title}";
}

/**
 * Override the page annotation url
 *
 * @param string $hook   'extender:url'
 * @param string $type   'annotation'
 * @param string $url    current return value
 * @param array  $params supplied params
 *
 * @return void|string
 */
function pages_set_revision_url($hook, $type, $url, $params) {
	
	$annotation = elgg_extract('extender', $params);
	if ($annotation->getSubtype() == 'page') {
		return "pages/revision/{$annotation->id}";
	}
}

/**
 * Override the default entity icon for pages
 *
 * @param string $hook        'entity:icon:url'
 * @param string $type        'object'
 * @param string $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return string
 */
function pages_icon_url_override($hook, $type, $returnvalue, $params) {
	
	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggPage) {
		return elgg_get_simplecache_url('pages/images/pages.gif');
	}
}

/**
 * Add a menu item to the user ownerblock
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return ElggMenuItem[]
 */
function pages_owner_block_menu($hook, $type, $return, $params) {
	
	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggUser) {
		$url = "pages/owner/{$entity->username}";
		$item = new ElggMenuItem('pages', elgg_echo('pages'), $url);
		$return[] = $item;
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->pages_enable != "no") {
			$url = "pages/group/{$entity->guid}/all";
			$item = new ElggMenuItem('pages', elgg_echo('pages:group'), $url);
			$return[] = $item;
		}
	}
	
	return $return;
}

/**
 * Add links/info to entity menu particular to pages plugin
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:entity'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function pages_entity_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	if (!$entity->canEdit()) {
		return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'history',
		'icon' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => "pages/history/{$entity->guid}",
	]);

	return $return;
}

/**
 * Prepare a notification message about a new page
 *
 * @param string                          $hook         'prepare'
 * @param string                          $type         'notification:create:object:page' | 'notification:create:object:page_top'
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 *
 * @return void|Elgg\Notifications\Notification
 */
function pages_prepare_notification($hook, $type, $notification, $params) {
	
	$event = elgg_extract('event', $params);
	
	$entity = $event->getObject();
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	$owner = $event->getActor();
	$recipient = elgg_extract('recipient', $params);
	$language = elgg_extract('language', $params);
	$method = elgg_extract('method', $params);

	$descr = $entity->description;
	$title = $entity->getDisplayName();

	$notification->subject = elgg_echo('pages:notify:subject', [$title], $language);
	$notification->body = elgg_echo('pages:notify:body', [
		$owner->getDisplayName(),
		$title,
		$descr,
		$entity->getURL(),
	], $language);
	$notification->summary = elgg_echo('pages:notify:summary', [$entity->getDisplayName()], $language);
	$notification->url = $entity->getURL();
	
	return $notification;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook        'permissions_check'
 * @param string $type        'object'
 * @param bool   $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void|bool
 */
function pages_write_permission_check($hook, $type, $returnvalue, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	$write_permission = (int) $entity->write_access_id;
	$user = elgg_extract('user', $params);

	if (empty($write_permission) || !$user instanceof ElggUser) {
		return;
	}
	
	switch ($write_permission) {
		case ACCESS_PRIVATE:
			// Elgg's default decision is what we want
			return;
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

/**
 * Extend container permissions checking to extend container write access for write users.
 *
 * @param string $hook        'container_permissions_check'
 * @param string $type        'object'
 * @param bool   $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void|bool
 */
function pages_container_permission_check($hook, $type, $returnvalue, $params) {
	
	$subtype = elgg_extract('subtype', $params);
	// check type/subtype
	if ($type !== 'object' || $subtype !== 'page') {
		return;
	}
	
	$container = elgg_extract('container', $params);
	$user = elgg_extract('user', $params);
	
	if (!$user instanceof ElggUser) {
		return;
	}
	
	// OK if you can write to the container
	if ($container instanceof ElggEntity && $container->canWriteToContainer($user->guid)) {
		return true;
	}

	// look up a page object given via input
	if ($page_guid = get_input('page_guid', 0)) {
		$page = get_entity($page_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$page = get_entity($parent_guid);
	}
	if (!$page instanceof ElggPage) {
		return;
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
 * @param string $hook         'get_views'
 * @param string $type         'ecml'
 * @param array  $return_value current return value
 * @param array  $params       supplied params
 *
 * @return array
 */
function pages_ecml_views_hook($hook, $type, $return_value, $params) {
	$return_value['object/page'] = elgg_echo('item:object:page');

	return $return_value;
}

/**
 * Is the given value a page object?
 *
 * @param ElggObject $value the entity to check
 *
 * @return bool
 * @access private
 */
function pages_is_page($value) {
	
	if (!$value instanceof ElggObject) {
		return false;
	}
	
	return in_array($value->getSubtype(), ['page', 'page_top']);
}

/**
 * Return options for the write_access_id input
 *
 * @param string $hook         'access:collections:write'
 * @param string $type         'user'
 * @param array  $return_value current return value
 * @param array  $params       supplied params
 *
 * @return void|array
 */
function pages_write_access_options_hook($hook, $type, $return_value, $params) {
	
	$input_params = elgg_extract('input_params', $params);
	if (empty($input_params) || !isset($return_value[ACCESS_PUBLIC])) {
		return;
	}
	
	if (elgg_extract('entity_subtype', $input_params) !== 'page') {
		return;
	}

	if (elgg_extract('purpose', $input_params) !== 'write') {
		return;
	}
	
	unset($return_value[ACCESS_PUBLIC]);
	
	return $return_value;
}

/**
 * Called on view_vars, input/access hook
 * Prevent ACCESS_PUBLIC from ending up as a write access option
 *
 * @param string $hook   'view_vars'
 * @param string $type   'input/access'
 * @param array  $return current return value
 * @param array  $params supplied params
 *
 * @return void|array
 */
function pages_write_access_vars($hook, $type, $return, $params) {
	
	if (elgg_extract('name', $return) !== 'write_access_id' || elgg_extract('purpose', $return) !== 'write') {
		return;
	}
	
	$value = (int) elgg_extract('value', $return);
	if ($value !== ACCESS_PUBLIC && $value !== ACCESS_DEFAULT) {
		return;
	}
	
	$default_access = get_default_access();
	
	if ($value === ACCESS_PUBLIC || $default_access === ACCESS_PUBLIC) {
		// is the value public, or default which resolves to public?
		// if so we'll set it to logged in, the next most permissible write access level
		$return['value'] = ACCESS_LOGGED_IN;
	}
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'pages_init');
};
