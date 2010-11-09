<?php
/**
 * Elgg default annotation view
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$owner = get_user($vars['annotation']->owner_guid);
$icon = elgg_view("profile/icon", array('entity' => $owner, 'size' => 'small'));

$info = elgg_view("output/longtext", array("value" => $vars['annotation']->value));

echo elgg_view_listing($icon, $info);