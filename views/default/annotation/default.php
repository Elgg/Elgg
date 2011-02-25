<?php
/**
 * Elgg default annotation view
 *
 * @uses $vars['annotation']
 */

$owner = get_user($vars['annotation']->owner_guid);
$icon = elgg_view_entity_icon($owner, 'tiny');

$info = elgg_view("output/longtext", array("value" => $vars['annotation']->value));

echo elgg_view_image_block($icon, $info);
