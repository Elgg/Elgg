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
);

$count = elgg_get_entities($options);

if ($count) {
	$replies_text = elgg_echo('group:replies');
	echo "<span class=\"elgg-river-comments-tab\">$replies_text</span>";

	$list_options = array(
		'order_by' => 'e.time_created desc',
		'list_class' => 'elgg-river-comments',
		'pagination' => false,
		'count' => false,
		'limit' => 3,
	);

	$options = array_merge($options, $list_options);

	echo elgg_list_entities($options);

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