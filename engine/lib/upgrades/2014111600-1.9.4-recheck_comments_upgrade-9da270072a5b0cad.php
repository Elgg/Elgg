<?php
/**
 * Elgg 1.9.4 upgrade 2014111600
 * recheck_comments_upgrade
 *
 * The discussion reply upgrade had a bug that caused it to mark also the
 * comments upgrade as completed. This rechecks whether there still are
 * unmigrated comment annotations left and marks the upgrade as incomplete
 * if annotations are found.
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

$comments = elgg_get_annotations(array(
	'annotation_names' => 'generic_comment',
	'count' => true
));

if ($comments) {
	$factory = new ElggUpgrade();

	$upgrade = $factory->getUpgradeFromPath("admin/upgrades/comments");

	if ($upgrade) {
		$upgrade->setPrivateSetting('is_completed', 0);

		_elgg_create_notice_of_pending_upgrade(null, null, $upgrade);
	}
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
