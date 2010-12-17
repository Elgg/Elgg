<?php
/**
 * Controls on an river item
 * 
 *
 * @uses $vars['item']
 */

$object = $vars['item']->getObjectEntity();

if (isloggedin()) {
	// comments and non-objects cannot be commented on
	if ($object->getType() == 'object' && $vars['item']->annotation_id == 0) {
		$params = array(
			'href' => '#',
			'text' => elgg_echo('generic_comments:text'),
			'class' => 'elgg-toggle',
			'internalid' => "elgg-toggler-{$object->getGUID()}",
		);
		echo elgg_view('output/url', $params);
		//echo elgg_view('forms/likes/link', array('entity' => $object));
	}
}