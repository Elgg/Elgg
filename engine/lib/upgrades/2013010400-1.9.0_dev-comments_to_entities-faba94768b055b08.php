<?php
/**
 * Elgg 1.9.0-dev upgrade 2013010400
 * comments_to_entities
 *
 * Convert comment annotations to entities.
 *
 * Register comment subtype and add ElggUpgrade for ajax upgrade.
 *
 * We do not migrate comments in this upgrade. See the comment
 * upgrade action in actions/admin/upgrades/upgrade_comments.php for that.
 */

// Register subtype and class for comments
if (get_subtype_id('object', 'comment')) {
	update_subtype('object', 'comment', 'ElggComment');
} else {
	add_subtype('object', 'comment', 'ElggComment');
}

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

// add ElggUpgrade object if need to migrate comments
$options = array(
	'annotation_names' => 'generic_comment',
	'order_by' => 'n_table.id DESC',
	'count' => true
);

if (elgg_get_annotations($options)) {
	$path = "admin/upgrades/comments";
	$upgrade = new ElggUpgrade();

	// Create the upgrade if one with the same URL doesn't already exist
	if (!$upgrade->getUpgradeFromPath($path)) {
		$upgrade->setPath($path);
		$upgrade->title = 'Comments Upgrade';
		$upgrade->description = 'Comments have been improved in Elgg 1.9 and require a migration. Run this upgrade to complete the migration.';
		$upgrade->save();
	}
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
