<?php
/**
 * Displays a url input field
 */

$vars['class'] = 'elgg-input-url';
$vars['type'] = 'url';

$attrs = elgg_format_attributes($vars);

echo "<input $attrs>";
