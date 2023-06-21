<?php
/**
 * Layout footer
 *
 * @uses $vars['footer'] Footer view
 */

$footer = elgg_extract('footer', $vars);
if (empty($footer)) {
	return;
}

echo elgg_format_element('div', ['class' => ['elgg-foot', 'elgg-layout-footer']], $footer);
