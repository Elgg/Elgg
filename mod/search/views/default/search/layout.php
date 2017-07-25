<?php
/**
 * The default search layout
 *
 * @uses $vars['body']
 */

echo elgg_view_layout('one_sidebar', [
	'title' => $vars['title'],
	'content' => $vars['body']
]);
