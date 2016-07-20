<?php
$ipsum = elgg_view('developers/ipsum');

$user = new ElggUser();
$image = elgg_view_entity_icon($user, 'small');
$image_alt = elgg_view_icon('user-plus');
echo elgg_view_image_block($image, "$ipsum $ipsum $ipsum $ipsum $ipsum $ipsum $ipsum", [
	'class' => 'theme-sandbox-image-block',
	'data-type' => 'user',
	'image_alt' => $image_alt,
]);
