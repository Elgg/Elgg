<?php
/**
 * Blog archives
 */

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
$page = elgg_extract('page', $vars);

if ($page === 'friends') {
	// don't show archive links on friends' blogs page
	return;
}

$content = elgg_view_menu('blog_archive', [
	'page' => $page,
	'entity' => $entity,
	'class' => 'elgg-menu-page',
]);

if (!$content) {
	return;
}

echo elgg_view_module('aside', elgg_echo('blog:archives'), $content);
