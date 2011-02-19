<?php
/**
 * History of this page
 *
 * @uses $vars['page']
 */

$title = elgg_echo('pages:history');

if ($vars['page']) {
	$options = array(
		'guid' => $vars['page']->guid,
		'annotation_name' => 'page',
		'limit' => 20,
		'reverse_order_by' => true
	);
	$content = elgg_list_annotations($options);
}

echo elgg_view_module('aside', $title, $content);