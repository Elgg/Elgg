<?php

$class = (array) elgg_extract("class", $vars);
$class[] = "elgg-icon";

$vars["class"] = $class;

$attributes = elgg_format_attributes($vars);

echo "<span $attributes></span>";