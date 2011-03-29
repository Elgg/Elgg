<?php
/**
 * Elgg notifications plugin index
 *
 * @package ElggNotifications
 */

// Load Elgg framework
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// Ensure only logged-in users can see this page
gatekeeper();

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$user = elgg_get_page_owner_guid();

$js_url = elgg_get_simplecache_url('js', 'friendsPickerv1');
elgg_register_js('friendsPickerv1', $js_url);
elgg_load_js('friendsPickerv1');

// Set the context to settings
elgg_set_context('settings');

$title = elgg_echo('notifications:subscriptions:changesettings');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
elgg_push_breadcrumb($title);

// Get the form
$people = array();
if ($people_ents = elgg_get_entities_from_relationship(array('relationship' => 'notify', 'relationship_guid' => elgg_get_logged_in_user_guid(), 'types' => 'user', 'limit' => 99999))) {
	foreach($people_ents as $ent) {
		$people[] = $ent->guid;
	}
}

$body = elgg_view('notifications/subscriptions/form', array('people' => $people));

$params = array(
	'content' => $body,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
