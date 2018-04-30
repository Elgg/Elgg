<?php

/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['menu']         Module menu do display in the header
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */
$type = elgg_extract('type', $vars, false);
$title = elgg_extract('title', $vars, '');
$body = elgg_extract('body', $vars, '');
$footer = elgg_extract('footer', $vars, '');
$show_inner = elgg_extract('show_inner', $vars, false);

$module = new \Elgg\Markup\Block();

$module->id = elgg_extract('id', $vars);
$module->addClass(elgg_extract_class($vars), 'elgg-module');

if ($type) {
	$module->addClass("elgg-module-$type");
}

$heading = null;
if ($title) {
	$heading = \Elgg\Markup\Heading::h3($title);
} else {
	$header = elgg_extract('header', $vars);

	if ($header != null) {
		$heading = new \Elgg\Markup\Html($header);
	}
}

if ($heading) {
	$header = new \Elgg\Markup\Block($heading, ['class' => 'elgg-head']);

	$menu = elgg_extract('menu', $vars);
	if ($menu) {
		$header->append($menu, ['class' => 'elgg-module-menu']);
	}

	$module->append($header);
}

$module->append(new \Elgg\Markup\Block($body, ['class' => 'elgg-body']));

if ($footer) {
	$module->append(new \Elgg\Markup\Block($footer, ['class' => 'elgg-foot']));
}

if ($show_inner) {
	$module->wrapChildren(\Elgg\Markup\Block::class, ['class' => 'elgg-inner']);
}

echo $module;
