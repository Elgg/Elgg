<?php
/**
 * Restore disappeared subpages. This is caused by its parent page being deleted
 * when the parent page is a top level page. We take advantage of the fact that
 * the parent_guid was deleted for the subpages.
 *
 * This upgrade script will no longer work once we have converted all pages to
 * have the same entity subtype.
 */


/**
 * Update subtype
 *
 * @param ElggObject $page
 */
function pages_2012061800($page) {
	$dbprefix = elgg_get_config('dbprefix');
	$subtype_id = (int)get_subtype_id('object', 'page_top');
	$page_guid = (int)$page->guid;
	update_data("UPDATE {$dbprefix}entities
		SET subtype = $subtype_id WHERE guid = $page_guid");
	error_log("called");
	return true;
}

$previous_access = elgg_set_ignore_access(true);

$dbprefix = elgg_get_config('dbprefix');
$name_metastring_id = get_metastring_id('parent_guid');
if (!$name_metastring_id) {
	return;
}

// Looking for pages without metadata
$options = array(
	'type' => 'object',
	'subtype' => 'page',
	'wheres' => "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}metadata md
		WHERE md.entity_guid = e.guid
		AND md.name_id = $name_metastring_id)"
);
$batch = new ElggBatch('elgg_get_entities_from_metadata', $options, 'pages_2012061800', 50, false);
elgg_set_ignore_access($previous_access);

if ($batch->callbackResult) {
	error_log("Elgg Pages upgrade (2012061800) succeeded");
}
