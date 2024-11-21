<?php
/**
 * Edit/create a group wrapper
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entity = elgg_extract('entity', $vars);

echo elgg_view_form('groups/edit', ['sticky_enabled' => true], ['entity' => $entity]);
