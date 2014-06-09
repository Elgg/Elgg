<?php
/**
 * List the latest reports
 */

$list = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => $vars['entity']->num_display,
	'pagination' => false,
));
if (!$list) {
	$list = '<p class="mtm">' . elgg_echo('reportedcontent:none') . '</p>';
}

echo $list;
