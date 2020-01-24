<?php
/**
 * Edit/create a group wrapper
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entity = elgg_extract('entity', $vars, null);

$form_vars = [
	'prevent_double_submit' => true,
	'class' => 'elgg-form-alt',
];

echo elgg_view_form('groups/edit', $form_vars, groups_prepare_form_vars($entity));
