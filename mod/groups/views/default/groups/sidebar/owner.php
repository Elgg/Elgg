<?php
/**
 * Group owner sidebar
 *
 * @uses $vars['entity'] Group entity
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggGroup)) {
	return;
}

$owner = $entity->getOwnerEntity();
if (!($owner instanceof \ElggUser)) {
	return;
}

$body = elgg_view_entity($owner, [
	'full_view' => false,
]);

echo elgg_view_module('aside', elgg_echo('groups:owner'), $body);
