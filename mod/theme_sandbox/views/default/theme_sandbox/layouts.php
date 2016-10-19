<?php
/**
 * Layouts
 */

echo 'Select the layout you wish to use looking at the sandbox pages:';
echo '<ul><li>';
echo elgg_view('output/url', [
	'href' => elgg_http_add_url_query_elements(current_page_url(), [
		'layout' => 'one_column',
	]),
	'text' => 'One Column',
]);
echo '</li><li>';
echo elgg_view('output/url', [
	'href' => elgg_http_add_url_query_elements(current_page_url(), [
		'layout' => 'one_sidebar',
	]),
	'text' => 'One Sidebar',
]);
echo '</li><li>';
echo elgg_view('output/url', [
	'href' => elgg_http_add_url_query_elements(current_page_url(), [
		'layout' => 'two_sidebar',
	]),
	'text' => 'Two Sidebar',
]);
echo '</li></ul>';
