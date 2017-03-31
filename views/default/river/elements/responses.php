<?php

/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */
// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return;
}

elgg_require_js('river/elements/responses');

$item = $vars['item'];
/* @var ElggRiverItem $item */
$object = $item->getObjectEntity();

// annotations and comments do not have responses
if ($item->annotation_id != 0 || !$object || $object instanceof ElggComment) {
	return;
}

$comment_count = $object->countComments();

$card_blocks = '';
$card_class = [
	'card',
	'elgg-river-responses-card',
];

if ($comment_count) {
	$comments = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $object->getGUID(),
		'limit' => 3,
		'order_by' => 'e.time_created desc',
		'distinct' => false,
	]);

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	$pagination = false;
	if ($comment_count > 3) {
		$pagination = elgg_view('navigation/more', [
			'#class' => 'elgg-river-more card-block',
			'href' => $object->getURL() . '#comments',
			'text' => elgg_echo('river:comments:all', [$comment_count]),
		]);
	}
	
	$card_blocks .= elgg_view_entity_list($comments, [
		'list_class' => 'elgg-river-comments list-group-flush',
		'pagination' => $pagination,
	]);
}

if ($object->canComment()) {
	$form_vars = [
		'id' => "comments-add-{$object->guid}-{$item->id}",
		'class' => 'card-block hidden',
	];
	$body_vars = [
		'entity' => $object,
	];
	if (empty($card_blocks)) {
		$card_class[] = 'hidden';
	}
	$card_blocks .= elgg_view_form('comment/save', $form_vars, $body_vars);
}

if ($card_blocks) {
	echo elgg_format_element('div', [
		'class' => $card_class,
			], $card_blocks);
}
