<?php
/**
 * Elgg 1.11.2 upgrade 2015062900
 * discussion_plugin
 *
 * Discussion feature was pulled from groups plugin into its
 * own plugin, so we need to update references of subtype
 * 'groupforumtopic' into 'discussion'.
 */

$dbprefix = elgg_get_config('dbprefix');

// Update subtype "groupforumtopic" into "discussion"
update_data("UPDATE {$dbprefix}entity_subtypes
	SET subtype = 'discussion'
	WHERE type = 'object' AND subtype = 'groupforumtopic'");

// Update river items to use the new view and subtype
update_data("UPDATE {$dbprefix}river
	SET view = 'river/object/discussion/create', subtype = 'discussion'
	WHERE type = 'object' AND subtype = 'groupforumtopic'");

// Update system log to use the new subtype
update_data("UPDATE {$dbprefix}system_log
	SET object_subtype = 'discussion'
	WHERE object_type = 'object' AND object_subtype = 'groupforumtopic'");

// If groups plugin is enabled, enable also the discussion plugin
// so the feature won't disappear from groups that are using it.
if (elgg_is_active_plugin('groups')) {
	// Force Elgg to discover the new plugin in plugins directory
	// and create a new \ElggPlugin entity for it so it can be
	// found with elgg_get_plugin_from_id().
	_elgg_generate_plugin_entities();

	$plugin = elgg_get_plugin_from_id('discussions');
	$plugin->activate();
}
