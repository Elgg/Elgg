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

	$item = new ElggMenuItem('pages', elgg_echo('pages'), 'pg/pages/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	register_page_handler('pages', 'pages_page_handler');

	// Register a url handler
	register_entity_url_handler('pages_url', 'object', 'page_top');
	register_entity_url_handler('pages_url', 'object', 'page');
	register_extender_url_handler('pages_revision_url', 'annotation', 'page');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'pages/actions/pages';
	elgg_register_action("pages/edit", "$action_base/edit.php");
	elgg_register_action("pages/editwelcome", "$action_base/editwelcome.php");
	elgg_register_action("pages/delete", "$action_base/delete.php");

	// Extend some views
	elgg_extend_view('css/screen', 'pages/css');

	// Register entity type for search
	register_entity_type('object', 'page');
	register_entity_type('object', 'page_top');

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
		'write_access_id' => 'access',
	));

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'pages_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'pages_ecml_views_hook');
}

/**
 * Dispatcher for pages.
 * URLs take the form of
 *  All pages:        pg/pages/all
 *  User's pages:     pg/pages/owner/<username>
 *  Friends' pages:   pg/pages/friends/<username>
 *  View page:        pg/pages/view/<guid>/<title>
 *  New page:         pg/pages/add/<guid> (container: user, group, parent)
 *  Edit page:        pg/pages/edit/<guid>
 *  History of page:  pg/pages/history/<guid>
 *  Revision of page: pg/pages/revision/<id>
 *  Group pages:      pg/pages/group/<guid>/owner
 *
 * Title is ignored
 *
 * @param array $page
 */
function pages_page_handler($page) {

	elgg_load_library('elgg:pages');
	
	// add the jquery treeview files for navigation
	$js_url = elgg_get_site_url() . 'mod/pages/vendors/jquery-treeview/jquery.treeview.min.js';
	elgg_register_js($js_url, 'jquery-treeview');
	$css_url = elgg_get_site_url() . 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css($css_url, 'jquery-treeview');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('pages'), 'pg/pages/all');

	$base_dir = elgg_get_plugins_path() . 'pages';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$owner = get_user_by_username($page[1]);
			set_input('guid', $owner->guid);
			include "$base_dir/index.php";
			break;
		case 'friends':
			set_input('username', $page[1]);
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
			set_input('guid', $page[1]);
			include "$base_dir/index.php";
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
	return "pg/pages/view/$entity->guid/$title";
}

/**
 * Override the page annotation url
 *
 * @param ElggAnnotation $annotation
 * @return string
 */
function pages_revision_url($annotation) {
	return "pg/pages/revision/$annotation->id";
}

/**
 * Add a menu item to the user ownerblock
 */
function pages_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "pg/pages/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('pages', elgg_echo('pages'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->pages_enable != "no") {
			$url = "pg/pages/group/{$params['entity']->guid}/owner";
			$item = new ElggMenuItem('pages', elgg_echo('pages:group'), $url);
			$return[] = $item;
		}
	}

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
		$url = elgg_get_site_url() . "pg/view/" . $entity->guid;
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
			if (can_write_to_container(get_loggedin_userid(), elgg_get_page_owner_guid())) return true;
		}
		if ($page_guid = get_input('page_guid',0)) {
			$entity = get_entity($page_guid);
		} else if ($parent_guid = get_input('parent_guid',0)) {
			$entity = get_entity($parent_guid);
		}
		if ($entity instanceof ElggObject) {
			if (
					can_write_to_container(get_loggedin_userid(), $entity->container_guid)
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
