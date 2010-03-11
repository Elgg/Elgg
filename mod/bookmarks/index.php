<?php
/**
 * Elgg bookmarks plugin index page
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

$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}

elgg_push_breadcrumb(elgg_echo('bookmarks:all'), $CONFIG->wwwroot."mod/bookmarks/all.php");
elgg_push_breadcrumb(sprintf(elgg_echo("bookmarks:user"),$page_owner->name));

//set bookmarks header
if(page_owner() == get_loggedin_userid()) {
	$area1 .= elgg_view('page_elements/content_header', array('context' => "own", 'type' => 'bookmarks'));
} else {
	$area1 .= elgg_view('navigation/breadcrumbs');
	$area1 .= elgg_view('page_elements/content_header_member', array('type' => 'bookmarks'));
}

// List bookmarks
set_context('search');
$bookmarks = list_entities('object','bookmarks',page_owner());
if(!$bookmarks && ($page_owner->guid == get_loggedin_user()->guid))
	$bookmarks = elgg_view('help/bookmarks');
$area2 .= $bookmarks;
set_context('bookmarks');

//if the logged in user is not looking at their stuff, display the ownerblock
if(page_owner()	!= get_loggedin_user()->guid){
	$area3 = elgg_view('bookmarks/ownerblock');
}else{
	if(isloggedin()){
		// if logged in, get the bookmarklet
		$area3 .= elgg_view("bookmarks/bookmarklet");
	}
}
//include a view for plugins to extend
$area3 .= elgg_view("bookmarks/sidebar_options", array("object_type" => 'bookmarks'));
// Format page
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);

// Draw it
echo page_draw(sprintf(elgg_echo("bookmarks:user"),page_owner_entity()->name), $body);