<?php
/**
 * Displays discussion replies for a discussion river item
 */

$topic = elgg_extract('topic', $vars);

$options = array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->guid,
	'distinct' => false,
	'order_by' => 'e.time_created desc',
);

$max_responses = 3;

// we request one more than we need to determine if we need to show the "more" link
$options['limit'] = $max_responses + 1;
$responses = elgg_get_entities($options);

if ($responses) {
	$response_count = count($responses);

	if ($response_count > $max_responses) {
		// may be more, sadly we have to count
		$response_count = elgg_get_entities($options + ['count' => true]);
		// we have to cut the extra one
		array_pop($responses);
	}

	// why is this reversing it? because we're asking for the MAX latest
	// responses by sorting desc and limiting by MAX, but we want to display
	// these responses with the latest at the bottom.
	$responses = array_reverse($responses);

	echo elgg_view_entity_list($responses, array('list_class' => 'elgg-river-comments'));

	if ($response_count > $max_responses) {
		$num_more_responses = $response_count - $max_responses;
		$url = $topic->getURL();
		$params = array(
			'href' => $url,
			'text' => elgg_echo('river:comments:more', array($num_more_responses)),
			'is_trusted' => true,
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more\">$link</div>";
	}
}

$form_vars = array('id' => "discussion-reply-{$topic->guid}", 'class' => 'hidden');
$body_vars = array('topic' => $topic, 'inline' => true);
echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);