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
	elgg_register_library('elgg:pages', elgg_get_plugins_path() . 'pages/lib/pages.php');

	$item = new ElggMenuItem('pages', elgg_echo('pages'), 'pages/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('pages', 'pages_page_handler');

	// Register a url handler
	elgg_register_plugin_hook_handler('entity:url', 'object', 'pages_set_url');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'pages_set_url');
	elgg_register_plugin_hook_handler('extender:url', 'annotation', 'pages_set_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'pages/actions';
	elgg_register_action("pages/edit", "$action_base/pages/edit.php");
	elgg_register_action("pages/delete", "$action_base/pages/delete.php");
	elgg_register_action("annotations/page/delete", "$action_base/annotations/page/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'pages/css');

	elgg_define_js('jquery.treeview', array(
		'src' => '/mod/pages/vendors/jquery-treeview/jquery.treeview.min.js',
		'exports' => 'jQuery.fn.treeview',
		'deps' => array('jquery'),
	));
	$css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery.treeview', $css_url);

	// Register entity type for search
	elgg_register_entity_type('object', 'page');
	elgg_register_entity_type('object', 'page_top');

	// Register for notifications
	elgg_register_notification_event('object', 'page');
	elgg_register_notification_event('object', 'page_top');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page', 'pages_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:page_top', 'pages_prepare_notification');

	// add to groups
	add_group_tool_option('pages', elgg_echo('groups:enablepages'), true);
	elgg_extend_view('groups/tool_latest', 'pages/group_module');

	//add a widget
	elgg_register_widget_type('pages', elgg_echo('pages'), elgg_echo('pages:widget:description'));

	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	elgg_set_config('pages', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'parent_guid' => 'parent',
		'access_id' => 'access',

		// TODO change to "access" when input/write_access is removed
		'write_access_id' => 'write_access',
	));

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

	$base_dir = elgg_get_plugins_path() . 'pages/pages/pages';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $page[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit.php";
			break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $page[1]);
			include "$base_dir/history.php";
			break;
		case 'revision':
			set_input('id', $page[1]);
			include "$base_dir/revision.php";
			break;
		case 'all':
			include "$base_dir/world.php";
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
				return 'mod/pages/images/pages.gif';
				break;
			default:
				return 'mod/pages/images/pages_lrg.gif';
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

	$options = array(
		'name' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => "pages/history/$entity->guid",
		'priority' => 150,
	);
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

	$notification->subject = elgg_echo('pages:notify:subject', array($title), $language); 
	$notification->body = elgg_echo('pages:notify:body', array(
		$owner->name,
		$title,
		$descr,
		$entity->getURL(),
	), $language);
	$notification->summary = elgg_echo('pages:notify:summary', array($entity->title), $language);

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
	if (elgg_get_context() != "pages") {
		return null;
	}
	if (elgg_get_page_owner_guid()
			&& can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) {
		return true;
	}
	if ($page_guid = get_input('page_guid', 0)) {
		$entity = get_entity($page_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$entity = get_entity($parent_guid);
	}
	if (isset($entity) && pages_is_page($entity)) {
		if (can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
				|| in_array($entity->write_access_id, get_access_list())) {
			return true;
		}
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
	return ($value instanceof ElggObject) && in_array($value->getSubtype(), array('page', 'page_top'));
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
			|| !in_array($params['input_params']['entity_subtype'], array('page', 'page_top'))) {
		return null;
	}

	if ($params['input_params']['purpose'] === 'write') {
		unset($return_value[ACCESS_PUBLIC]);
		return $return_value;
	}
}
