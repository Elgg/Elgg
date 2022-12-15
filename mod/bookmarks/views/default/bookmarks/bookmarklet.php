<?php
/**
 * Bookmarklet
 */

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('bookmarks:this');
if ($page_owner instanceof \ElggGroup) {
	$title = elgg_echo('bookmarks:this:group', [$page_owner->getDisplayName()]);
}

$code = elgg_view('bookmarks/bookmarklet.js');

$base_url = elgg_generate_url('add:object:bookmarks', [
	'guid' => $page_owner->guid,
]);
$base_url_str = json_encode($base_url, JSON_UNESCAPED_SLASHES);
$code = str_replace('BASEURL', $base_url_str, $code);

$bookmarklet = elgg_view('output/url', [
	'text' => $title,
	'icon' => 'thumbtack',
	'href' => "javascript:{$code}",
	'onclick' => 'return false',
	'class' => 'elgg-button elgg-button-action',
	'style' => 'cursor:move',
	'allowed_schemes' => ['javascript'],
]);

echo elgg_format_element('p', [], elgg_echo('bookmarks:bookmarklet:description'));
echo elgg_format_element('p', [], $bookmarklet);
echo elgg_format_element('p', [], elgg_echo('bookmarks:bookmarklet:descriptionie'));
echo elgg_format_element('p', [], elgg_echo('bookmarks:bookmarklet:description:conclusion'));
