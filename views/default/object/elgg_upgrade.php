<?php
/**
 * ElggUpgrade view
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);

// don't pass title so it will be automatically linkified
$params = array(
	'entity' => $entity,
	'subtitle' => $entity->description,
);

$body = elgg_view('object/elements/summary', $params + $vars);

echo elgg_view_image_block(false, $body, $vars);
