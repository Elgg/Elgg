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
	return true;
}

$item = $vars['item'];
$object = $item->getObjectEntity();
$target = $item->getTargetEntity();

// annotations and comments do not have responses
if ($item->annotation_id != 0 || !$object || elgg_instanceof($target, 'object', 'comment')) {
	return true;
}

$comment_count = $object->countComments();

if ($comment_count) {
	$comments = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $object->getGUID(),
		'limit' => 3,
		'order_by' => 'e.time_created desc'
	));

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

?>
	<span class="elgg-river-comments-tab"><?php echo elgg_echo('comments'); ?></span>

<?php

	echo elgg_view_entity_list($comments, array('list_class' => 'elgg-river-comments'));

	if ($comment_count > count($comments)) {
		$num_more_comments = $comment_count - count($comments);
		$url = $object->getURL();
		$params = array(
			'href' => $url,
			'text' => elgg_echo('river:comments:more', array($num_more_comments)),
			'is_trusted' => true,
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more\">$link</div>";
	}
}

// inline comment form
$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
$body_vars = array('entity' => $object, 'inline' => true);
echo elgg_view_form('comment/save', $form_vars, $body_vars);