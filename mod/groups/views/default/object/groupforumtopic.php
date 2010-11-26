<?php

/**
 * Elgg Groups latest discussion listing
 *
 * @package ElggGroups
 */

//get the required variables
$title = htmlentities($vars['entity']->title, ENT_QUOTES, 'UTF-8');
$topic_owner = get_user($vars['entity']->owner_guid);
$group = get_entity($vars['entity']->container_guid);
$forum_created = elgg_view_friendly_time($vars['entity']->time_created);
$counter = $vars['entity']->countAnnotations("group_topic_post");
$last_post = $vars['entity']->getAnnotations("group_topic_post", 1, 0, "desc");

//get the time and user
if ($last_post) {
	foreach($last_post as $last) {
		$last_time = $last->time_created;
		$last_user = $last->owner_guid;
	}
}

$u = get_user($last_user);

//select the correct output depending on where you are
if (get_context() == "search") {

	$info = "<p class=\"latest_discussion_info\">" . sprintf(elgg_echo('group:created'), $forum_created, $counter) .  "<br /><span class=\"timestamp\">";
	if (($last_time) && ($u)) $info.= sprintf(elgg_echo('groups:lastupdated'), elgg_view_friendly_time($last_time), " <a href=\"" . $u->getURL() . "\">" . $u->name . "</a>");
	$info .= '</span></p>';
	//get the group avatar
	$icon = elgg_view("profile/icon",array('entity' => $group, 'size' => 'small'));
	//get the group and topic title
	if ($group instanceof ElggGroup) {
		$info .= "<p>" . elgg_echo('group') . ": <a href=\"{$group->getURL()}\">".htmlentities($group->name, ENT_QUOTES, 'UTF-8') ."</a></p>";
	}

	$info .= "<p>" . elgg_echo('groups:topic') . ": <a href=\"{$vars['entity']->getURL()}\">{$title}</a></p>";

} else {

	$info = "<span class=\"latest_discussion_info\"><span class=\"timestamp\">" . sprintf(elgg_echo('group:created'), $forum_created, $counter) . "</span>";
	if ($last_time && $u) {
		$commenter_link = "<a href\"{$u->getURL()}\">$u->name</a>";
		$text = sprintf(elgg_echo('groups:lastcomment'), elgg_view_friendly_time($last_time), $commenter_link);
		$info .= "<br /><span class='timestamp'>$text</span>";
	}

	if (groups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {

		// edit link
		$info .= "<br /><span class='timestamp'>" . elgg_view("output/url", array(
				'href' => $vars['url'] . "pg/forum/edit/{$vars['entity']->guid}",
				'text' => elgg_echo('edit'),
				)) . "</span>&nbsp;";

		// delete link
		$info .= "<span class=\"timestamp\">" . elgg_view("output/confirmlink", array(
				'href' => $vars['url'] . "action/groups/deletetopic?topic=" . $vars['entity']->guid . "&group=" . $vars['entity']->container_guid,
				'text' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				)) . "</span>";

	}

	$info .= "</span>";

	//get the user avatar
	$icon = elgg_view("profile/icon",array('entity' => $topic_owner, 'size' => 'small'));
	$info .= "<p>" . elgg_echo('groups:started') . " " . $topic_owner->name . ": <a href=\"{$vars['entity']->getURL()}\">{$title}</a></p>";
	$info .= "<div class='clearfloat'></div>";

}

echo elgg_view_listing($icon, $info);
