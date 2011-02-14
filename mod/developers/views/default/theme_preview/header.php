<?php
/**
 * Header for theme preview pages
 *
 * @uses $vars['title']
 * @uses $vars['page']
 * @uses $vars['pages']
 */
$index_url = 'pg/theme_preview/';
$index_url = elgg_normalize_url($index_url);

$url = current_page_url();

$title = elgg_echo("theme_preview:{$vars['page']}");

$index = array_search($vars['page'], $vars['pages']);

$previous = '< previous';
if ($index > 0) {
	$previous = elgg_view('output/url', array(
		'href' => "pg/theme_preview/{$vars['pages'][$index - 1]}",
		'text' => $previous,
	));
}

$next = 'next >';
if ($index < (count($vars['pages']) - 1)) {
	$next = elgg_view('output/url', array(
		'href' => "pg/theme_preview/{$vars['pages'][$index + 1]}",
		'text' => $next,
	));
}

echo <<<HTML
<h1 class="mbs">
	<a href="$index_url">Index</a> >
	<a href="$url">$title</a>
</h1>
<div class="mbl">
	$previous&nbsp;&nbsp;$next
</div>
HTML;
