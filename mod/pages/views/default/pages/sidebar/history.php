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

echo elgg_view_module('aside', $title, $content);