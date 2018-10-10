<?php
/**
 * Search box
 *
 * @uses $vars['value'] Current search query
 * @uses $vars['class'] Additional class
 */

if (elgg_in_context('search')) {
	return;
}

$class = elgg_extract_class($vars, "elgg-search");
unset($vars['class']);

echo elgg_view_form('search', [
	'action' => elgg_generate_url('default:search'),
	'method' => 'get',
	'disable_security' => true,
	'class' => $class,
], $vars);
