<?php

/**
 * Profile layout alt sidebar
 *
 * @uses $vars['entity']
 */

$meta_block = elgg_view('page/elements/meta_block', $vars);
if ($meta_block) {
	echo elgg_format_element('div', [
		'class' => 'card',
	], $meta_block);
}

$page_menu = elgg_view('page/elements/menu', $vars);
if ($page_menu) {
	echo elgg_format_element('div', [
		'class' => 'card',
	], $page_menu);
}