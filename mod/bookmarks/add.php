<?php
/**
 * Elgg bookmarks plugin add bookmark page
 * 
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

global $CONFIG;

// Start engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// You need to be logged in for this one
gatekeeper();
		
// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}
if ($page_owner instanceof ElggGroup)
	$container = $page_owner->guid;
			
//set up breadcrumbs
$area1 .= elgg_view('page_elements/breadcrumbs', array( 
		'breadcrumb_root_url' => $CONFIG->wwwroot."mod/bookmarks/all.php",
		'breadcrumb_root_text' => elgg_echo('bookmarks:all'),
		'breadcrumb_currentpage' => elgg_echo("bookmarks:add")
		)); 
	
// get the filter menu
$area1 .= elgg_view('page_elements/content_header', array('context' => "action", 'type' => 'bookmarks'));
			
// If we've been given a bookmark to edit, grab it
if ($this_guid = get_input('bookmark',0)) {
	$entity = get_entity($this_guid);
	if ($entity->canEdit()) {
		$area2 .= elgg_view('bookmarks/form',array('entity' => $entity, 'container_guid' => $container));
	}
} else {
	$area2 .= elgg_view('bookmarks/form', array('container_guid' => $container));
}

$area3 = elgg_view('bookmarks/ownerblock');
// if logged in, get the bookmarklet
$area3 .= elgg_view("bookmarks/bookmarklet");		
//include a view for plugins to extend
$area3 .= elgg_view("bookmarks/sidebar_options", array("object_type" => 'bookmarks'));	
		
// Format page
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);
		
// Draw it
echo page_draw(elgg_echo('bookmarks:add'),$body);