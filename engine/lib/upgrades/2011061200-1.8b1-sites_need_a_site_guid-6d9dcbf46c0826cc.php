<?php
/**
 * Elgg 1.8b1 upgrade 2011061200
 * sites_need_a_site_guid
 *
 * Sites did not have a site guid. This causes problems with getting
 * metadata on site objects since we default to the current site.
 */

global $CONFIG;

$ia = elgg_set_ignore_access(true);
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$options = array(
	'type' => 'site',
	'site_guid' => 0,
);
$batch = new ElggBatch('elgg_get_entities', $options);

foreach ($batch as $entity) {
	if (!$entity->site_guid) {
		update_data("UPDATE {$CONFIG->dbprefix}entities SET site_guid=$entity->guid
				WHERE guid=$entity->guid");
	}
}

access_show_hidden_entities($access_status);
elgg_set_ignore_access($ia);
