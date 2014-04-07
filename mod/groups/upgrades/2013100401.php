<?php

// Register subtype and class for comments
if (get_subtype_id('object', 'discussion_reply')) {
	update_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
} else {
	add_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
}

/**
 * The actual upgrade will be run from the view admin/groups/upgrades/2013100401.php
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

$discussion_replies = elgg_get_annotations(array(
	'annotation_names' => 'group_topic_post',
));

// Notify administrator only if there are existing discussion replies
if ($discussion_replies) {
	$upgrade = new ElggUpgrade();
	$upgrade->setURL("admin/groups/upgrades/2013100401");
	$upgrade->title = 'Group Discussions Upgrade';
	$upgrade->description = 'Group discussions have been improved in Elgg 1.9 and require a migration. Run this upgrade to complete the migration.';
	$upgrade->save();
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
