<?php
/**
 * Elgg friends page
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (!$owner = page_owner_entity()) {
	gatekeeper();
	set_page_owner($_SESSION['user']->getGUID());
	$owner = $_SESSION['user'];
}
$friends = sprintf(elgg_echo("friends:owned"),$owner->name);

$area1 = elgg_view_title($friends);
$area2 = "<div class='members_list'>".list_entities_from_relationship('friend',$owner->getGUID(),false,'user','',0,10,false)."</div>";
$body = elgg_view_layout('one_column_with_sidebar', $area1 . $area2);

page_draw($friends, $body);
