<?php
/**
 * Edit/create a group
 */

$entity = elgg_extract('entity', $vars, null);

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = array('entity' => $entity);
echo elgg_view_form('groups/edit', $form_vars, $body_vars);

if ($entity) {
	echo '<div class="delete_group">';
	echo elgg_view_form('groups/delete', array(), array('entity' => $entity));
	echo '</div>';
}
