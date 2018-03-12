<?php
$ipsum = elgg_view('developers/ipsum');
$ipsum = "$ipsum $ipsum $ipsum $ipsum $ipsum $ipsum $ipsum";

$user = new ElggUser();
$user->username = 'theme_sandbox';
$image = elgg_view_entity_icon($user, 'small');

echo elgg_view_image_block($image, $ipsum, [
	'class' => 'theme-sandbox-image-block',
	'data-type' => 'user',
]);
