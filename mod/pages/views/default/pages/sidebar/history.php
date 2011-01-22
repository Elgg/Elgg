<?php
/**
 * History of this page
 *
 * @uses $vars['page']
 */

$title = elgg_echo('pages:history');

if ($vars['page']) {
	$content = $content = list_annotations($vars['page']->guid, 'page', 20, false);
}

echo elgg_view('layout/objects/module', array(
	'title' => $title,
	'body' => $content,
	'class' => 'elgg-aside-module',
));

