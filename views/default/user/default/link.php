<?php
/**
 * ElggUser rendered as a link
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = $vars['entity'];
/* @var ElggUser $entity */

echo elgg_view('output/url', array(
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
	'is_trusted' => true,
	'class' => 'elgg-format-link elgg-format-link-user',
));
