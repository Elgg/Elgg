<?php
/**
 * Elgg image view
 *
 * @uses string $vars['src'] The image src url (required).
 * @uses string $vars['alt'] The alternate text for the image (required).
 */

if (!isset($vars['alt'])) {
	elgg_log("The view output/img requires that the alternate text be set.", 'NOTICE');
}

$vars['src'] = elgg_normalize_url($vars['src']);
$vars['src'] = elgg_format_url($vars['src']);

$attributes = elgg_format_attributes($vars);
echo "<img $attributes/>";
