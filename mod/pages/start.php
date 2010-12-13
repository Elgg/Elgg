<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

/**
 * Initialise the pages plugin.
 *
 */
function pages_init() {
	global $CONFIG;

	add_menu(elgg_echo('pages'), "mod/pages/world.php");

	// Register a page handler, so we can have nice URLs
	register_page_handler('pages','pages_page_handler');

	// Register a url handler
	register_entity_url_handler('pages_url','object', 'page_top');
	register_entity_url_handler('pages_url','object', 'page');

	// Register some actions
	elgg_register_action("pages/edit", $CONFIG->pluginspath . "pages/actions/pages/edit.php");
	elgg_register_action("pages/editwelcome", $CONFIG->pluginspath . "pages/actions/pages/editwelcome.php");
	elgg_register_action("pages/delete", $CONFIG->pluginspath . "pages/actions/pages/delete.php");

	// Extend some views
	elgg_extend_view('css/screen','pages/css');
	elgg_extend_view('groups/menu/links', 'pages/menu'); // Add to groups context
	elgg_extend_view('groups/right_column', 'pages/groupprofile_pages'); // Add to groups context

	// Register entity type
	register_entity_type('object','page');
	register_entity_type('object','page_top');

	// Register granular notification for this type
	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'page', elgg_echo('pages:new'));
		register_notification_object('object', 'page_top', elgg_echo('pages:new'));
	}

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'page_notify_message');

	// add the group pages tool option
	add_group_tool_option('pages',elgg_echo('groups:enablepages'),true);

	//add a widget
	elgg_register_widget_type('pages',elgg_echo('pages'),elgg_echo('pages:widget:description'));

	// For now, we'll hard code the groups profile items as follows:
	// TODO make this user configurable

	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	$CONFIG->pages = array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'access_id' => 'access',
		'write_access_id' => 'access',
	);

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'pages_ecml_views_hook');
}

function pages_url($entity) {

	$title = elgg_get_friendly_title($entity->title);
	return "pg/pages/view/{$entity->guid}/$title";
}

/**
 * Sets up submenus for the pages system.  Triggered on pagesetup.
 *
 */
function pages_submenus() {

	global $CONFIG;

	$page_owner = elgg_get_page_owner();

	// Group submenu option
		if ($page_owner instanceof ElggGroup && elgg_get_context() == 'groups') {
			if($page_owner->pages_enable != "no"){
				add_submenu_item(elgg_echo("pages:group", array($page_owner->name)), "pg/pages/owned/" . $page_owner->username);
			}
		}
}

/**
 * Pages page handler.
 *
 * @param array $page
 */
function pages_page_handler($page) {
	global $CONFIG;

	if (isset($page[0])) {
		// See what context we're using
		switch($page[0]) {
			case "new" :
				include($CONFIG->pluginspath . "pages/new.php");
				break;

			case "welcome" :
				if (isset($page[1])) {
					set_input('username', $page[1]);
				}
				include($CONFIG->pluginspath . "pages/welcome.php");
				break;

			case "world":
				include($CONFIG->pluginspath . "pages/world.php");
				break;
			case "owned" :
				// Owned by a user
				if (isset($page[1])) {
					set_input('username',$page[1]);
				}

				include($CONFIG->pluginspath . "pages/index.php");
				break;

			case "edit" :
				if (isset($page[1])) {
					set_input('page_guid', $page[1]);
				}

				$entity = get_entity($page[1]);
				add_submenu_item(elgg_echo('pages:label:view'), "pg/pages/view/{$page[1]}", 'pageslinks');
				// add_submenu_item(elgg_echo('pages:user'), elgg_get_site_url() . "pg/pages/owned/" . get_loggedin_user()->username, 'pageslinksgeneral');
				if (($entity) && ($entity->canEdit())) {
					add_submenu_item(elgg_echo('pages:label:edit'), "pg/pages/edit/{$page[1]}", 'pagesactions');
				}
				add_submenu_item(elgg_echo('pages:label:history'), "pg/pages/history/{$page[1]}", 'pageslinks');

				include($CONFIG->pluginspath . "pages/edit.php");
				break;

			case "view" :
				if (isset($page[1])) {
					set_input('page_guid', $page[1]);
				}

				elgg_extend_view('html_head/extend','pages/metatags');

				$entity = get_entity($page[1]);
				//add_submenu_item(elgg_echo('pages:label:view'), "pg/pages/view/{$page[1]}", 'pageslinks');
				if (($entity) && ($entity->canEdit())) {
					add_submenu_item(elgg_echo('pages:label:edit'), "pg/pages/edit/{$page[1]}", 'pagesactions');
				}

				add_submenu_item(elgg_echo('pages:label:history'), "pg/pages/history/{$page[1]}", 'pageslinks');

				include($CONFIG->pluginspath . "pages/view.php");
				break;

			case "history" :
				if (isset($page[1])) {
					set_input('page_guid', $page[1]);
				}

				elgg_extend_view('html_head/extend','pages/metatags');

				$entity = get_entity($page[1]);
				add_submenu_item(elgg_echo('pages:label:view'), "pg/pages/view/{$page[1]}", 'pageslinks');
				if (($entity) && ($entity->canEdit())) {
					add_submenu_item(elgg_echo('pages:label:edit'), "pg/pages/edit/{$page[1]}", 'pagesactions');
				}
				add_submenu_item(elgg_echo('pages:label:history'), "pg/pages/history/{$page[1]}", 'pageslinks');

				include($CONFIG->pluginspath . "pages/history.php");
				break;

			default:
				include($CONFIG->pluginspath . "pages/new.php");
				break;
		}
	}
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
		global $CONFIG;
		$url = elgg_get_site_url() . "pg/view/" . $entity->guid;
		if ($method == 'sms') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $url . ' (' . $title . ')';
		}
		if ($method == 'email') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
		if ($method == 'site') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
	}
	return null;
}


/**
 * Sets the parent of the current page, for navigation purposes
 *
 * @param ElggObject $entity
 */
function pages_set_navigation_parent(ElggObject $entity) {
	$guid = $entity->getGUID();

	while ($parent_guid = $entity->parent_guid) {
		$entity = get_entity($parent_guid);
		if ($entity) {
			$guid = $entity->getGUID();
		}
	}

	set_input('treeguid',$guid);
}

function pages_get_path($guid) {

	if (!$entity = get_entity($guid)) {
		return array();
	}

	$path = array($guid);

	while ($parent_guid = $entity->parent_guid) {
		$entity = get_entity($parent_guid);
		if ($entity) {
			$path[] = $entity->getGUID();
		}
	}

	return $path;
}

/**
 * Return the correct sidebar for a given entity
 *
 * @param ElggObject $entity
 */
function pages_get_entity_sidebar(ElggObject $entity, $fulltree = 0)
{
	$body = "";

	$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' => $entity->guid, 'limit' => 9999));
	$body .= elgg_view('pages/sidebar/sidebarthis', array('entity' => $entity,
														'children' => $children,
														'fulltree' => $fulltree));
	//$body = elgg_view('pages/sidebar/wrapper', array('body' => $body));

	return $body;
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

// write permission plugin hooks
elgg_register_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');

// Make sure the pages initialisation function is called on initialisation
elgg_register_event_handler('init','system','pages_init');
elgg_register_event_handler('pagesetup','system','pages_submenus');