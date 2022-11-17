<?php
/**
 * Edit/create a group wrapper
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entity = elgg_extract('entity', $vars);

$form_vars = [
	'class' => 'elgg-form-alt',
	'prevent_double_submit' => ($entity instanceof \ElggGroup), // don't prevent double submit when creating a group. This is to help with navigation
	'sticky_enabled' => true,
];

echo elgg_view_form('groups/edit', $form_vars, ['entity' => $entity]);
