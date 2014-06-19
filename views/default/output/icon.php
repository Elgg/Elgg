<?php
/**
* Display an icon from the elgg icons sprite.
*
* @package Elgg
* @subpackage Core
*
* @uses $vars['class'] Class of elgg-icon
*/

$class = (array) elgg_extract("class", $vars);
$class[] = "elgg-icon";

$vars["class"] = $class;

$attributes = elgg_format_attributes($vars);

echo "<span $attributes></span>";
