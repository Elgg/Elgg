<?php
/**
 * Group delete form body
 */

$warning = elgg_echo("groups:deletewarning");

echo elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $vars['entity']->getGUID(),
));

echo elgg_view('input/submit', array(
	'class' => "elgg-button elgg-button-delete",
	'value' => elgg_echo('groups:delete'),
	'onclick' => "return confirm('$warning');",
));
