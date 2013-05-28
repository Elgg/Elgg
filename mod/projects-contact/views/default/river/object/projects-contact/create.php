<?php
/**
* New project contact river entry
*
* @package project-contact
*/

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'attachments' => elgg_view('output/url', array('href' => $object->address)),
));
