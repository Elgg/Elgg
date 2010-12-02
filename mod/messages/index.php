<?php
/**
 * Elgg messages inbox page
 *
 * @package ElggMessages
*/


require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();
global $CONFIG;

$offset = get_input('offset', 0);
$limit = 10;

// Get the logged in user, you can't see other peoples messages so use session id
$page_owner = get_loggedin_user();
set_page_owner($page_owner->getGUID());

// Get the user's inbox, this will be all messages where the 'toId' field matches their guid
// @todo - fix hack where limit + 1 messages are requested
$messages = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name' => 'toId',
	'metadata_value' => $page_owner->getGUID(),
	'owner_guid' => $page_owner->guid,
	'limit' => $limit + 1,
	'offset' => $offset
));

// Set the page title
$area2 = "<div id='content_header'><div class='content-header-title'>";
$area2 .= elgg_view_title(elgg_echo("messages:inbox"))."</div>";
$area2 .= "<div class='content-header-options'><a class='action-button' href='".elgg_get_site_url()."mod/messages/send.php'>" . elgg_echo('messages:compose') . "</a></div></div>";

// Display them. The last variable 'page_view' is to allow the view page to know where this data is coming from,
// in this case it is the inbox, this is necessary to ensure the correct display
$area2 .= elgg_view("messages/forms/view",array('entity' => $messages, 'page_view' => "inbox", 'limit' => $limit, 'offset' => $offset));

// Sidebar menu options
//$area3 = elgg_view("messages/menu_options", array('context' => 'inbox'));

// format
$body = elgg_view_layout("one_column_with_sidebar", array('content' => $area2));


// Draw page
echo elgg_view_page(elgg_echo('messages:user', array($page_owner->name)), $body);
