<?php
/**
 * View a file
 *
 * @package ElggFile
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'file');

$file = get_entity($guid);
/* @var $file ElggFile */

echo elgg_view_profile_page($file);
