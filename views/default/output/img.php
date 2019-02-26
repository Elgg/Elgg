<?php
/**
 * Elgg image view
 *
 * @uses string $vars['src'] The image src url (required).
 * @uses string $vars['alt'] The alternate text for the image (required).
 */

$src = elgg_extract('src', $vars);
if (empty($src)) {
	return;
}

if (!isset($vars['alt'])) {
	elgg_log("The view output/img requires that the alternate text be set.", 'NOTICE');
}

$vars['src'] = elgg_normalize_url($src);

echo elgg_format_element('img', $vars);
