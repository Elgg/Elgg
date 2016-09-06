<?php

/**
 * Wrap form footer
 * @uses $vars['footer']      Form footer
 * @uses $vars['action_name'] Action name
 */
$footer = elgg_extract('footer', $vars);
if (empty($footer)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-foot elgg-form-footer',
		], $footer);
