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
	elgg_register_entity_url_handler('object', 'page_top', 'pages_url');
	elgg_register_entity_url_handler('object', 'page', 'pages_url');
	elgg_register_annotation_url_handler('page', 'pages_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'pages/actions/pages';
	elgg_register_action("pages/edit", "$action_base/edit.php");
	elgg_register_action("pages/editwelcome", "$action_base/editwelcome.php");
	elgg_register_action("pages/delete", "$action_base/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'pages/css');

	// Register javascript needed for sidebar menu
	$js_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.min.js';
	elgg_register_js('jquery-treeview', $js_url);
	$css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery-treeview', $css_url);

	// Register entity type for search
	elgg_register_entity_type('object', 'page');
	elgg_register_entity_type('object', 'page_top');

	// Register granular notification for this type
	register_notification_object('object', 'page', elgg_echo('pages:new'));
	register_notification_object('object', 'page_top', elgg_echo('pages:new'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'page_notify_message');

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
		'access_id' => 'access',
		'write_access_id' => 'write_access',
	));

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'pages_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');

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
 */
function pages_page_handler($page) {

	elgg_load_library('elgg:pages');
	
	// add the jquery treeview files for navigation
	elgg_load_js('jquery-treeview');
	elgg_load_css('jquery-treeview');

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
		default:
			include "$base_dir/world.php";
			break;
	}

	return;
}

/**
 * Override the page url
 * 
 * @param ElggObject $entity Page object
 * @return string
 */
function pages_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "pages/view/$entity->guid/$title";
}

/**
 * Override the page annotation url
 *
 * @param ElggAnnotation $annotation
 * @return string
 */
function pages_revision_url($annotation) {
	return "pages/revision/$annotation->id";
}

/**
 * Override the default entity icon for pages
 *
 * @return string Relative URL
 */
function pages_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'page_top') ||
		elgg_instanceof($entity, 'object', 'page')) {
		switch ($params['size']) {
			case 'small':
				return 'mod/pages/images/pages.gif';
				break;
			case 'medium':
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

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'pages') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
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
* Returns a more meaningful message
*
* @param unknown_type $hook
* @param unknown_type $entity_type
* @param unknown_type $returnvalue
* @param unknown_type $params
*/
function page_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && (($entity->getSubtype() == 'page_top') || ($entity->getSubtype() == 'page'))) {
		$descr = $entity->description;
		$title = $entity->title;
		//@todo why?
		$url = elgg_get_site_url() . "view/" . $entity->guid;
		$owner = $entity->getOwnerEntity();
		return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
	}
	return null;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function pages_write_permission_check($hook, $entity_type, $returnvalue, $params)
{
	if ($params['entity']->getSubtype() == 'page'
		|| $params['entity']->getSubtype() == 'page_top') {

		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];

		if (($write_permission) && ($user)) {
			// $list = get_write_access_array($user->guid);
			$list = get_access_array($user->guid); // get_access_list($user->guid);

			if (($write_permission!=0) && (in_array($write_permission,$list))) {
				return true;
			}
		}
	}
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function pages_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == "pages") {
		if (elgg_get_page_owner_guid()) {
			if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) return true;
		}
		if ($page_guid = get_input('page_guid',0)) {
			$entity = get_entity($page_guid);
		} else if ($parent_guid = get_input('parent_guid',0)) {
			$entity = get_entity($parent_guid);
		}
		if ($entity instanceof ElggObject) {
			if (
					can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
					|| in_array($entity->write_access_id,get_access_list())
				) {
					return true;
			}
		}
	}

}

/**
 * Return views to parse for pages.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function pages_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/page'] = elgg_echo('item:object:page');
	$return_value['object/page_top'] = elgg_echo('item:object:page_top');

	return $return_value;
}
