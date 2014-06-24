<?php
/**
 * Displays a text input field
 */

$vars['class'] = 'elgg-input-text';
$vars['type'] = 'text';

$attrs = elgg_format_attributes($vars);

echo "<input $attrs>";
