<?php

// Register subtype and class for comments
if (get_subtype_id('object', 'discussion_reply')) {
	update_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
} else {
	add_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
}

/**
 * The actual upgrade will be run from the view /mod/groups/admin/groups/upgrades/2013100401.php
 */
 
$access_status = access_get_show_hidden_status();
$ia = elgg_set_ignore_access(true);
$discussions = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'groupforumtopic',
));
elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);

// Notify administrator only if there are existing discussions
if ($discussions) {
	$migrate_link = elgg_view('output/url', array(
		'href' => 'admin/groups/upgrades/2013100401',
		'text' => "run a migration script",
		'is_trusted' => true,
	));

	// Not using translation because new keys won't be in the cache
	elgg_add_admin_notice('discussion_migration_notice', "The data structure of discussion replies has changed in Elgg 1.9. You must $migrate_link before you can use the discussions tool.");
}

