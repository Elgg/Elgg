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
		'limit' => max(20, elgg_get_config('default_limit')),
		'reverse_order_by' => true
	);
	elgg_push_context('widgets');
	$content = elgg_list_annotations($options);
}

echo elgg_view_module('aside', $title, $content);