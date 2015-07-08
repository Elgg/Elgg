<?php
/**
 * Blog sidebar menu showing revisions
 *
 * @package Blog
 */

//If editing a post, show the previous revisions and drafts.
$blog = elgg_extract('entity', $vars, FALSE);

if (!elgg_instanceof($blog, 'object', 'blog')) {
	return;
}

if (!$blog->canEdit()) {
	return;
}

$owner = $blog->getOwnerEntity();
$revisions = array();

$auto_save_annotations = $blog->getAnnotations([
	'annotation_name' => 'blog_auto_save',
	'limit' => 1,
]);
if ($auto_save_annotations) {
	$revisions[] = $auto_save_annotations[0];
}

$saved_revisions = $blog->getAnnotations([
	'annotation_name' => 'blog_revision',
	'reverse_order_by' => true,
	'limit' => false
]);

$revisions = array_merge($revisions, $saved_revisions);

if (empty($revisions)) {
	return;
}

$load_base_url = "blog/edit/{$blog->getGUID()}";

// show the "published revision"
$published_item = '';
if ($blog->status == 'published') {
	$load = elgg_view('output/url', [
		'href' => $load_base_url,
		'text' => elgg_echo('status:published'),
		'is_trusted' => true,
	]);

	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($blog->time_created));
	$published_item = elgg_format_element('li', [], "$load: $time");
}

$n = count($revisions);
$revisions_list = '';
foreach ($revisions as $revision) {
	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($revision->time_created));

	if ($revision->name == 'blog_auto_save') {
		$revision_lang = elgg_echo('blog:auto_saved_revision');
	} else {
		$revision_lang = elgg_echo('blog:revision') . " $n";
	}
	
	$load = elgg_view('output/url', array(
		'href' => "$load_base_url/$revision->id",
		'text' => $revision_lang,
		'is_trusted' => true,
	));

	$revisions_list .= elgg_format_element('li', ['class' => 'auto-saved'], "$load: $time");
	
	$n--;
}

$body = elgg_format_element('ul', ['class' => 'blog-revisions'], $published_item . $revisions_list);

echo elgg_view_module('aside', elgg_echo('blog:revisions'), $body);
