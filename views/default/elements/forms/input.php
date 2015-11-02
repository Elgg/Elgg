<?php

/**
 * Helper view that can be used to filter vars for all input views
 */
$input_type = elgg_extract('input_type', $vars);
unset($vars['input_type']);

echo elgg_view("input/$input_type", $vars);
