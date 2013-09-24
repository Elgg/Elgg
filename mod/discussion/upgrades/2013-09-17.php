<?php
/**
 * The actual upgrade will be run from the view /mod/discussion/admin/upgrades/discussion.php
 */

$ia = elgg_set_ignore_access(true);
$discussions = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'groupforumtopic',
));
elgg_set_ignore_access($ia);

// Notify administrator only if there are existing discussions
if ($discussions) {
	$migrate_link = elgg_view('output/url', array(
		'href' => 'admin/upgrades/discussion',
		'text' => "run a migration script",
		'is_trusted' => true,
	));

	// Not using translation because new keys won't be in the cache
	elgg_add_admin_notice('discussion_migration_notice', "Discussions tool was extracted from groups to its own plugin in Elgg 1.9. You must $migrate_link before you can use the new plugin.");
}