<?php
$ipsum = elgg_view('developers/ipsum');

$user = new ElggUser();
$image = elgg_view_entity_icon($user, 'small');
echo elgg_view_image_block($image, "$ipsum $ipsum $ipsum $ipsum $ipsum $ipsum $ipsum");
