<?php

$guid = get_input("guid");

if (!get_entity($guid)) {
	echo elgg_echo("error:missing_data");
	return;
}

$list = elgg_list_annotations(array(
	'guid' => $guid,
	'annotation_name' => 'likes',
	'limit' => 99,
	'preload_owners' => true,
	'pagination' => false,
));

echo elgg_format_element('div', ['class' => 'elgg-likes-popup'], $list);
