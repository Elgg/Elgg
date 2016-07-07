<?php
/**
 * Group edit form
 *
 * @package ElggGroups
 */

/* @var ElggGroup $entity */
$entity = elgg_extract("entity", $vars, false);

// context needed for input/access view
elgg_push_context("group-edit");

// build the group profile fields
echo elgg_view("groups/edit/profile", $vars);

// build the group access options
echo elgg_view("groups/edit/access", $vars);

// build the group tools options
echo elgg_view("groups/edit/tools", $vars);

elgg_pop_context();