<?php

echo elgg_view_page(elgg_echo('tagcloud:site_cloud'), [
	'content' => elgg_view_tagcloud([
		'threshold' => 0,
		'limit' => 100,
		'tag_name' => 'tags',
	]),
	'filter_id' => 'tagcloud',
]);
