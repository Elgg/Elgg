<?php
/**
 * Elgg default annotation view
 */

$owner = get_user($vars['annotation']->owner_guid);
$icon = elgg_view("profile/icon", array('entity' => $owner, 'size' => 'tiny'));

$info = elgg_view("output/longtext", array("value" => $vars['annotation']->value));

echo elgg_view_image_block($icon, $info);
