<?php
/**
 * Displays a password input field
 */

$vars['class'] = 'elgg-input-password';
$vars['type'] = 'password';

$attrs = elgg_format_attributes($vars);

echo "<input $attrs>";
