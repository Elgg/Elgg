<?php
/**
 * Displays discussion replies for a discussion river item
 */

$topic = elgg_extract('topic', $vars);

$options = array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->guid,
	'count' => true,
	'distinct' => false,
);

$count = elgg_get_entities($options);

if ($count) {
	$replies = elgg_get_entities(array_merge($options, [
		'order_by' => 'e.time_created desc',
		'count' => false,
		'limit' => 3,
	]));

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$replies = array_reverse($replies);

	echo elgg_view_entity_list($replies, array('list_class' => 'elgg-river-comments'));

	if ($count > 3) {
		$more_count = $count - 3;
		$params = array(
			'href' => $topic->getURL(),
			'text' => elgg_echo('river:comments:more', array($more_count)),
			'is_trusted' => true,
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more\">$link</div>";
	}
}

$form_vars = array('id' => "discussion-reply-{$topic->guid}", 'class' => 'hidden');
$body_vars = array('topic' => $topic, 'inline' => true);
echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);