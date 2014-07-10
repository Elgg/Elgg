<?php
/**
 * Displays an email input field
 */

$vars['class'] = 'elgg-input-email';
$vars['type'] = 'email';

$attrs = elgg_format_attributes($vars);

echo "<input $attrs>";
