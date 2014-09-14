<?php
/**
 * Elgg 1.9.0-dev upgrade 2014050600
 * replies_to_entities
 *
 * Registers discussion reply subtype and adds ElggUpgrade for ajax upgrade.
 *
 * We do not migrate discussion replies in this upgrade. See the upgrade action
 * in actions/admin/upgrades/upgrade_discussion_replies.php for that.
 *
 * This upgrade must be run even if the groups plugin is disabled because the
 * script will be removed in Elgg 1.10 and we don't want anyone to get stuck
 * with old annotation replies just because the groups plugin was not enabled
 * when site was upgraded from 1.8.
 */

// Register subtype and class for discussion replies
if (get_subtype_id('object', 'discussion_reply')) {
	update_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
} else {
	add_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
}

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

$discussion_replies = elgg_get_annotations(array(
	'annotation_names' => 'group_topic_post',
	'count' => true,
));

// Notify administrator only if there are existing discussion replies
if ($discussion_replies) {
	$path = "admin/upgrades/discussion_replies";
	$upgrade = new ElggUpgrade();

	// Create the upgrade if one with the same URL doesn't already exist
	if (!$upgrade->getUpgradeFromPath($path)) {
		$upgrade->setPath($path);
		$upgrade->title = 'Group Discussions Upgrade';
		$upgrade->description = 'Group discussions have been improved in Elgg 1.9 and require a migration. Run this upgrade to complete the migration.';
		$upgrade->save();
	}
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
