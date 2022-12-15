<?php

$ipsum = elgg_view('developers/ipsum');
$body = str_repeat($ipsum, 7);

$user = new \ElggUser();
$user->username = 'theme_sandbox';

$image = elgg_view_entity_icon($user, 'small');

echo elgg_view_image_block('', $body, [
	'class' => 'theme-sandbox-image-block',
	'data-type' => 'user',
	'image_alt' => $image,
]);
