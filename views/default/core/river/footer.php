<?php
/**
 * River item footer
 */

$item = $vars['item'];
$object = $item->getObjectEntity();

// annotations do not have comments
if ($item->annotation_id != 0 || !$object) {
	return true;
}

$comment_count = count_annotations($object->getGUID(), '', '', 'generic_comment');

$comments = get_annotations($object->getGUID(), "", "", 'generic_comment', "", "", 3, 0, "desc");
if ($comments) {
	$comments = array_reverse($comments);

?>
	<span class="elgg-river-comments-tab"><?php echo elgg_echo('comments'); ?></span>

<?php

	echo elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments'));

	if ($comment_count > count($comments)) {
		$num_more_comments = $comment_count - count($comments);
		$url = $object->getURL();
		$params = array(
			'href' => $url,
			'text' => elgg_echo('river:comments:more', array($num_more_comments)),
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more\">$link</div>";
	}
}

// inline comment form
$body = elgg_view('forms/comments/inline', array('entity' => $object));
$params = array(
	'body' => $body,
	'action' => 'action/comments/add',
	'internalid' => "elgg-togglee-{$object->getGUID()}",
);
echo elgg_view('input/form', $params);
