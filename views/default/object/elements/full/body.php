<?php

/**
 * Outputs object full view
 *
 * @uses $vars['body']        Body HTML
 * @uses $vars['body_params'] Vars used as attributes of the body wrapper
 */

$body = elgg_extract('body', $vars);
if (!$body) {
	return;
}

$body_params = elgg_extract('body_params', $vars, []);
$body_params['class'] = elgg_extract_class($body_params, ['elgg-listing-full-body', 'clearfix']);

echo elgg_format_element('div', $body_params, $body);
