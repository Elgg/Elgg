<?php
/**
 * Blog sidebar menu showing revisions
 *
 * @package Blog
 */

use Elgg\Database\Clauses\OrderByClause;

//If editing a post, show the previous revisions and drafts.
$blog = elgg_extract('entity', $vars, false);
if (!$blog instanceof ElggBlog) {
	return;
}

if (!$blog->canEdit()) {
	return;
}

$owner = $blog->getOwnerEntity();
$revisions = [];

$auto_save_annotations = $blog->getAnnotations([
	'annotation_name' => 'blog_auto_save',
	'limit' => 1,
]);
if ($auto_save_annotations) {
	$revisions[] = $auto_save_annotations[0];
}

$saved_revisions = $blog->getAnnotations([
	'annotation_name' => 'blog_revision',
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
	'limit' => false,
]);

$revisions = array_merge($revisions, $saved_revisions);
/* @var ElggAnnotation[] $revisions */

if (empty($revisions)) {
	return;
}

$load_base_url = elgg_generate_url('edit:object:blog', [
	'guid' => $blog->guid,
]);

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
	
	$load = elgg_view('output/url', [
		'href' => "$load_base_url/$revision->id",
		'text' => $revision_lang,
		'is_trusted' => true,
	]);

	$revisions_list .= elgg_format_element('li', ['class' => 'auto-saved'], "$load: $time");
	
	$n--;
}

$body = elgg_format_element('ul', ['class' => 'blog-revisions'], $published_item . $revisions_list);

echo elgg_view_module('aside', elgg_echo('blog:revisions'), $body);
