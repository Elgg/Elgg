<?php

/**
 * Elgg reported content send report page
 *
 * @package ElggReportedContent
 */

// Start engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// You need to be logged in for this one
gatekeeper();

// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	set_page_owner($page_owner->getGUID());
}

$area2 .= elgg_view_title(elgg_echo('reportedcontent:this'), false);

$area2 .= elgg_view('reportedcontent/form');

$body = elgg_view_layout('two_column_left_sidebar', '', $area2);

page_draw(elgg_echo('reportedcontent:add'),$body);
