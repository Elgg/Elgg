<?php
/**
 * List replies with optional add form
 *
 * @uses $vars['entity']        ElggEntity the group discission
 * @uses $vars['show_add_form'] Display add form or not
 */

$show_add_form = elgg_extract('show_add_form', $vars, true);

echo '<div id="group-replies" class="elgg-comments">';

$replies = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $vars['topic']->getGUID(),
	'reverse_order_by' => true,
	'distinct' => false,
	'url_fragment' => 'group-replies',
));

echo $replies;

if ($show_add_form) {
	$form_vars = array('class' => 'mtm');
	echo elgg_view_form('discussion/reply/save', $form_vars, $vars);
}

echo '</div>';
