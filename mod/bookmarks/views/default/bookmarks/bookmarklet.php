<?php
/**
 * Bookmarklet
 */

$page_owner = elgg_get_page_owner_entity();

if ($page_owner instanceof ElggGroup) {
	$title = elgg_echo("bookmarks:this:group", [$page_owner->getDisplayName()]);
} else {
	$title = elgg_echo("bookmarks:this");
}

$guid = $page_owner->guid;

$code = elgg_view('bookmarks/bookmarklet.js');

$base_url = elgg_generate_url('add:object:bookmarks', ['guid' => $guid]);
$base_url_str = json_encode($base_url, JSON_UNESCAPED_SLASHES);
$code = str_replace('BASEURL', $base_url_str, $code);

$bookmarklet = elgg_view('output/url', [
	'text' => $title,
	'icon' => 'thumbtack',
	'href' => "javascript:$code",
	'onclick' => 'return false',
	'class' => 'elgg-button elgg-button-action',
	'style' => 'cursor:move',
]);

?>
<p><?= elgg_echo("bookmarks:bookmarklet:description"); ?></p>
<p><?= $bookmarklet; ?></p>
<p><?= elgg_echo("bookmarks:bookmarklet:descriptionie"); ?></p>
<p><?= elgg_echo("bookmarks:bookmarklet:description:conclusion"); ?></p>
