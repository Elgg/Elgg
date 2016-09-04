<?php
/**
 * Displays a number input field
 */

$vars['class'] = 'elgg-input-number';
$vars['type'] = 'number';

$attrs = elgg_format_attributes($vars);

echo "<input $attrs>";
