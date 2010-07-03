<?php

include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$group_guid = (int) get_input('group_guid');
$group = get_entity($group_guid);
set_page_owner($group_guid);

$title = sprintf(elgg_echo('groups:membersof'), $group->name);

$area2 = elgg_view_title(elgg_echo('groups:memberlist'));

$area2 .= list_entities_from_relationship('member', $group_guid, true, 'user', '', 0, 10, false);

$body = elgg_view_layout('two_column_left_sidebar', '', $area2);

page_draw($title, $body);
