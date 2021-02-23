<?php
/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */

use Elgg\Database\Clauses\OrderByClause;

// allow river views to override the response content
$responses = elgg_extract('responses', $vars);
if (isset($responses)) {
	echo $responses;
	return;
}

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$object = $item->getObjectEntity();

// annotations and comments do not have responses
if (!empty($item->annotation_id) || !$object instanceof ElggEntity || $object instanceof ElggComment) {
	return;
}

$comment_count = $object->countComments();

if ($comment_count) {
	$comments = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $object->guid,
		'limit' => 3,
		'order_by' => [new OrderByClause('time_created', 'DESC')],
		'distinct' => false,
	]);

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);
	
	echo elgg_view_entity_list($comments, [
		'list_class' => 'elgg-river-comments',
		'show_excerpt' => true,
		'register_rss_link' => false,
	]);
	
	if ($comment_count > count($comments)) {
		$all_link = elgg_view_url($object->getURL(), elgg_echo('river:comments:all', [$comment_count]));
		echo elgg_format_element('div', ['class' => 'elgg-river-more'], $all_link);
	}
}

if (!$object->canComment()) {
	return;
}

// inline comment form
$form_vars = ['id' => "comments-add-{$object->guid}-{$item->id}", 'class' => 'hidden'];
$body_vars = ['entity' => $object, 'inline' => true];
echo elgg_view_form('comment/save', $form_vars, $body_vars);
