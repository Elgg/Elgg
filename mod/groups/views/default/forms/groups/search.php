<?php

$tag_string = elgg_echo('groups:search:tags');

$params = array(
	'internalname' => 'tag',
	'class' => 'search-input',
	'value' => $tag_string,
	'onclick' => "if (this.value=='$tag_string') { this.value='' }",
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search:go')));
