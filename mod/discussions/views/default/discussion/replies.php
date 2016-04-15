<?php
/**
 * List replies with optional add form
 *
 * @uses $vars['entity']        ElggEntity the group discission
 * @uses $vars['show_add_form'] Display add form or not
 */

$topic = elgg_extract('topic', $vars);
if (!elgg_instanceof($topic, 'object', 'discussion')) {
	elgg_log("discussion/replies view expects \$vars['topic'] to be a discussion object", 'ERROR');
	return;
}

$show_add_form = elgg_extract('show_add_form', $vars);
if (!isset($show_add_form)) {
	$show_add_form = $topic->canWriteToContainer(0, 'object', 'discussion_reply');
}

$replies = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->guid,
	'reverse_order_by' => true,
	'distinct' => false,
	'url_fragment' => 'group-replies',
));

if ($show_add_form) {
	$form_vars = array('class' => 'mtm');
	$replies .= elgg_view_form('discussion/reply/save', $form_vars, $vars);
}
?>

<div id="group-replies" class="elgg-comments">
	<?= $replies ?>
</div>
