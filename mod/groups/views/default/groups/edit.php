<?php
/**
 * Edit/create a group wrapper
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entity = elgg_extract('entity', $vars, null);

$form_vars = [
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form-alt',
];

echo elgg_view_form('groups/edit', $form_vars, groups_prepare_form_vars($entity));
