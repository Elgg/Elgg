<?php
/**
 * Restore disappeared subpages, which its parent page was top_page and was deleted,
 * by setting it's subtype to page_top.
 *
 */


/**
 * Condense first annotation into object
 *
 * @param ElggObject $page
 */
function pages_2012061800($page) {error_log($page->guid);
	$dbprefix = elgg_get_config('dbprefix');
	$subtype_id = add_subtype('object', 'page_top');
	update_data("UPDATE {$dbprefix}entities
		set subtype='$subtype_id' WHERE guid=$page->guid");
	return true;
}

$previous_access = elgg_set_ignore_access(true);

$dbprefix = elgg_get_config('dbprefix');
$name_metastring_id = get_metastring_id('parent_guid');

// Looking for pages without metadata (see #3046)
$options = array(
	'type' => 'object',
	'subtype' => 'page',
	'wheres' => "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}metadata md
		WHERE md.entity_guid = e.guid
		AND md.name_id = $name_metastring_id)"
);
$batch = new ElggBatch('elgg_get_entities_from_metadata', $options, 'pages_2012061800', 100);
elgg_set_ignore_access($previous_access);

if ($batch->callbackResult) {
	error_log("Elgg Pages upgrade (2012061800) succeeded");
} else {
	error_log("Elgg Pages upgrade (2012061800) failed");
}
