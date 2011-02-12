<?php
/**
 * Group delete form body
 */

$warning = elgg_echo("groups:deletewarning");

echo elgg_view('input/hidden', array(
	'internalname' => 'group_guid',
	'value' => $vars['entity']->getGUID(),
));

echo elgg_view('input/submit', array(
	'class' => "elgg-button-cancel",
	'value' => elgg_echo('groups:delete'),
	'onclick' => "javascript:return confirm('$warning')",
));
