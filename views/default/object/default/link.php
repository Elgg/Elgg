<?php
/**
 * ElggObject rendered as a link
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = $vars['entity'];
/* @var ElggObject $entity */

$title = $entity->getDisplayName();
if ($title === '') {
	$title = get_class($object);
}

echo elgg_view('output/url', array(
	'text' => $title,
	'href' => $entity->getURL(),
	'is_trusted' => true,
	'class' => 'elgg-format-link elgg-format-link-object',
));
