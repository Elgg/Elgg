<?php
/**
 * Blog sidebar menu showing revisions
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

/* @var ElggAnnotation[] $revisions */
$revisions = $blog->getAnnotations([
	'annotation_name' => 'blog_revision',
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
	'limit' => false,
]);

if (empty($revisions)) {
	return;
}

$load_base_url = elgg_generate_url('edit:object:blog', [
	'guid' => $blog->guid,
]);

// show the "published revision"
$published_item = '';
if ($blog->status == 'published') {
	$load = elgg_view_url($load_base_url, elgg_echo('status:published'));
	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($blog->time_created));

	$published_item = elgg_format_element('li', [], "$load: $time");
}

$n = count($revisions);
$revisions_list = '';
foreach ($revisions as $revision) {
	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($revision->time_created));
	
	$load = elgg_view_url("{$load_base_url}/{$revision->id}", elgg_echo('blog:revision') . " $n");

	$revisions_list .= elgg_format_element('li', ['class' => 'auto-saved'], "$load: $time");
	
	$n--;
}

$body = elgg_format_element('ul', ['class' => 'blog-revisions'], $published_item . $revisions_list);

echo elgg_view_module('aside', elgg_echo('blog:revisions'), $body);
