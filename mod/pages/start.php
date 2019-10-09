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
	\Elgg\Includer::requireFileOnce(__DIR__ . '/lib/pages.php');

	elgg_register_menu_item('site', [
		'name' => 'pages',
		'icon' => 'file-text-o',
		'text' => elgg_echo('collection:object:page'),
		'href' => elgg_generate_url('default:object:page'),
	]);

	// Register a url handler
	elgg_register_plugin_hook_handler('extender:url', 'annotation', 'pages_set_revision_url');

	// Register for notifications
	elgg_register_notification_event('object', 'page');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page', 'pages_prepare_notification');

	// add to groups
	elgg()->group_tools->register('pages');
	
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
	elgg_register_plugin_hook_handler('register', 'menu:pages_nav', '\Elgg\Pages\Menus::registerPageMenuItems');
	elgg_register_plugin_hook_handler('prepare', 'menu:pages_nav', '_elgg_setup_vertical_menu', 999);

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

	// register database seed
	elgg_register_plugin_hook_handler('seeds', 'database', 'pages_register_db_seeds');
}

/**
 * Override the page annotation url
 *
 * @param \Elgg\Hook $hook 'extender:url', 'annotation'
 *
 * @return void|string
 */
function pages_set_revision_url(\Elgg\Hook $hook) {
	
	$annotation = $hook->getParam('extender');
	if ($annotation->getSubtype() == 'page') {
		return elgg_generate_url('revision:object:page', [
			'id' => $annotation->id,
		]);
	}
}

/**
 * Override the default entity icon for pages
 *
 * @param \Elgg\Hook $hook 'entity:icon:url', 'object'
 *
 * @return string
 */
function pages_icon_url_override(\Elgg\Hook $hook) {
	if ($hook->getEntityParam() instanceof ElggPage) {
		return elgg_get_simplecache_url('pages/images/pages.gif');
	}
}

/**
 * Add a menu item to the user ownerblock
 *
 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
 *
 * @return ElggMenuItem[]
 */
function pages_owner_block_menu(\Elgg\Hook $hook) {
	
	$entity = $hook->getEntityParam();
	$return = $hook->getValue();
	
	if ($entity instanceof ElggUser) {
		$url = elgg_generate_url('collection:object:page:owner', [
			'username' => $entity->username,
		]);
		$item = new ElggMenuItem('pages', elgg_echo('collection:object:page'), $url);
		$return[] = $item;
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('pages')) {
			$url = elgg_generate_url('collection:object:page:group', [
				'guid' => $entity->guid,
				'subpage' => 'all',
			]);
			$item = new ElggMenuItem('pages', elgg_echo('collection:object:page:group'), $url);
			$return[] = $item;
		}
	}
	
	return $return;
}

/**
 * Add links/info to entity menu particular to pages plugin
 *
 * @param \Elgg\Hook $hook 'register', 'menu:entity'
 *
 * @return void|ElggMenuItem[]
 */
function pages_entity_menu_setup(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	if (!$entity->canEdit()) {
		return;
	}
	
	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([
		'name' => 'history',
		'icon' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => elgg_generate_url('history:object:page', [
			'guid' => $entity->guid,
		]),
	]);

	return $return;
}

/**
 * Prepare a notification message about a new page
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:page' | 'notification:create:object:page_top'
 *
 * @return void|Elgg\Notifications\Notification
 */
function pages_prepare_notification(\Elgg\Hook $hook) {
	
	$event = $hook->getParam('event');
	
	$entity = $event->getObject();
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	$owner = $event->getActor();
	$language = $hook->getParam('language');

	$descr = $entity->description;
	$title = $entity->getDisplayName();

	$notification = $hook->getValue();
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
 * @param \Elgg\Hook $hook 'permissions_check', 'object'
 *
 * @return void|bool
 */
function pages_write_permission_check(\Elgg\Hook $hook) {
	
	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggPage) {
		return;
	}
	
	$write_permission = (int) $entity->write_access_id;
	$user = $hook->getUserParam();

	if (empty($write_permission) || !$user instanceof ElggUser) {
		return;
	}
	
	switch ($write_permission) {
		case ACCESS_PRIVATE:
			// Elgg's default decision is what we want
			return;
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
 * Extend container permissions checking to extend container write access for write users, needed for personal pages
 *
 * @param \Elgg\Hook $hook 'container_permissions_check', 'object'
 *
 * @return void|bool
 */
function pages_container_permission_check(\Elgg\Hook $hook) {
	
	if ($hook->getValue()) {
		// already have access
		return;
	}
	
	// check type/subtype
	if ($hook->getType() !== 'object' || $hook->getParam('subtype') !== 'page') {
		return;
	}
	
	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	// look up a page object given via input
	$page_guid = (int) get_input('guid'); // defined by route
	if (empty($page_guid)) {
		// try the parent guid for use in the action
		$page_guid = (int) get_input('parent_guid');
	}
	if (empty($page_guid)) {
		return;
	}
	
	$page = get_entity($page_guid);
	if (!$page instanceof ElggPage) {
		return;
	}
	
	// check if the page write access is in the users read access array
	return in_array($page->write_access_id, get_access_array($user->guid));
}

/**
 * Return views to parse for pages.
 *
 * @param \Elgg\Hook $hook 'get_views', 'ecml'
 *
 * @return array
 */
function pages_ecml_views_hook(\Elgg\Hook $hook) {
	$return_value = $hook->getValue();
	$return_value['object/page'] = elgg_echo('item:object:page');
	return $return_value;
}

/**
 * Is the given value a page object?
 *
 * @param ElggObject $value the entity to check
 *
 * @return bool
 * @internal
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
 * @param \Elgg\Hook $hook 'access:collections:write', 'user'
 *
 * @return void|array
 */
function pages_write_access_options_hook(\Elgg\Hook $hook) {
	
	$input_params = $hook->getParam('input_params');
	$return_value = $hook->getValue();
	
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
 * @param \Elgg\Hook $hook 'view_vars', 'input/access'
 *
 * @return void|array
 */
function pages_write_access_vars(\Elgg\Hook $hook) {
	$return = $hook->getValue();
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

/**
 * Register database seed
 *
 * @param \Elgg\Hook $hook 'seeds', 'database'
 * @return array
 */
function pages_register_db_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\Pages\Seeder::class;

	return $seeds;
}

return function() {
	elgg_register_event_handler('init', 'system', 'pages_init');
};
