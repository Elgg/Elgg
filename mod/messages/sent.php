<?php
/**
* Elgg sent messages page
* 
* @package ElggMessages
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* @author Curverider Ltd <info@elgg.com>
* @copyright Curverider Ltd 2008-2010
* @link http://elgg.com/
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
global $CONFIG;

gatekeeper();

// Get the logged in user
$page_owner = get_loggedin_user();
set_page_owner($page_owner->guid);

// Get offset
$offset = get_input('offset',0);

// Set limit
$limit = 10;

// Display all the messages a user owns, these will make up the sentbox
$messages = elgg_get_entities_from_metadata(array('metadata_name' => 'fromId', 'metadata_value' => $_SESSION['user']->guid, 'types' => 'object', 'subtypes' => 'messages', 'owner_guid' => $page_owner->guid, 'limit' => $limit, 'offset' => $offset)); 


// Set the page title
$area2 = "<div id='content_header'><div class='content_header_title'>";
$area2 .= elgg_view_title(elgg_echo("messages:sentmessages"))."</div>";
$area2 .= "<div class='content_header_options'><a class='action_button' href='{$CONFIG->wwwroot}mod/messages/send.php'>" . elgg_echo('messages:compose') . "</a></div></div>";

// Set content
$area2 .= elgg_view("messages/forms/view",array('entity' => $messages, 'page_view' => "sent", 'limit' => $limit, 'offset' => $offset));

// Sidebar menu options
//$area3 = elgg_view("messages/menu_options", array('context' => 'sent'));

// Format
$body = elgg_view_layout("one_column_with_sidebar",'',$area2);

// Draw page
page_draw(sprintf(elgg_echo('messages:sentMessages'),$page_owner->name),$body);