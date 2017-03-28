<?php

/**
 * User wire post widget display view
 */
$widget = elgg_extract('entity', $vars);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'container_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => elgg_view('navigation/more', [
		'href' => "thewire/owner/" . $widget->getOwnerEntity()->username,
		'text' => elgg_echo('thewire:moreposts'),
		'is_trusted' => true,
	]),
	'list_class' => 'list-group-flush',
	'no_results' => elgg_echo('thewire:noposts'),
		]);

echo $content;
