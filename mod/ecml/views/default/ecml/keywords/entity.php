<?php
/**
 * ECML Generic Object GUID
 *
 * @package ECML
 */

$guid = $vars['guid'];

if ($entity = get_entity($guid)) {
	echo elgg_view('output/url', array(
		'href' => $entity->getURL(),
		'title' => $entity->title,
		'text' => $entity->title,
		'class' => "embeded_file link",
		// abusing the js attribute
		'js' => "style=\"background-image:url({$entity->getIcon('tiny')})\""

	));
} else {
	echo elgg_echo('ecml:entity:invalid');
}