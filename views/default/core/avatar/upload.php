<?php
/**
 * Avatar upload view
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

echo elgg_view('output/longtext', [
	'value' => elgg_echo('avatar:upload:instructions'),
]);

echo elgg_view_form('avatar/upload', [], $vars);
