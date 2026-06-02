<?php
/**
 * Blog sidebar menu showing revisions
 */

use Elgg\Database\Clauses\OrderByClause;

// If editing a post, show the previous revisions and drafts.
$blog = elgg_extract('entity', $vars, false);
if (!$blog instanceof \ElggBlog || !$blog->canEdit()) {
	return;
}

/** @var null|\ElggAnnotation $current_revision */
$current_revision = elgg_extract('revision', $vars);

/** @var ElggAnnotation[] $revisions */
$revisions = $blog->getAnnotations([
	'annotation_name' => 'blog_revision',
	'order_by' => [
		new OrderByClause('a_table.time_created', 'DESC'),
		new OrderByClause('a_table.id', 'DESC'),
	],
	'limit' => false,
]);

if (empty($revisions)) {
	return;
}

// show the "published revision"
$load = elgg_echo('blog:revisions:current');
if (!empty($current_revision)) {
	$load = elgg_view_url(elgg_generate_url('edit:object:blog', [
		'guid' => $blog->guid,
	]), elgg_echo('blog:revisions:current'));
}

$published_item = elgg_format_element('li', [], $load);

// list revisions
$n = count($revisions);
$revisions_list = '';
foreach ($revisions as $revision) {
	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($revision->time_created));
	
	if ($revision->id === $current_revision?->id) {
		$load = elgg_echo('blog:revision') . " {$n}";
	} else {
		$load = elgg_view_url(elgg_generate_url('edit:object:blog', [
			'guid' => $blog->guid,
			'revision' => $revision->id,
		]), elgg_echo('blog:revision') . " {$n}");
	}

	$revisions_list .= elgg_format_element('li', ['class' => 'auto-saved'], "{$load}: {$time}");
	
	$n--;
}

$body = elgg_format_element('ul', ['class' => 'blog-revisions'], $published_item . $revisions_list);

echo elgg_view_module('aside', elgg_echo('blog:revisions'), $body);
