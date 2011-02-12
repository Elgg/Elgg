<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 */

$show_add_form = elgg_get_array_value('show_add_form', $vars, true);

$id = '';
if (isset($vars['id'])) {
	$id = "id =\"{$vars['id']}\"";
}

echo "<div $id class=\"elgg-comments\">";

$options = array(
	'guid' => $vars['entity']->getGUID(),
	'annotation_name' => 'generic_comment'
);
echo elgg_list_annotations($options);

if ($show_add_form) {
	$form_vars = array('name' => 'elgg_add_comment');
	echo elgg_view_form('comments/add', $form_vars, $vars);
}

echo '</div>';
