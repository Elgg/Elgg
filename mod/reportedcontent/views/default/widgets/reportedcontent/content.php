<?php
/**
 * List the latest reports
 */

$list = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'reported_content',
	'limit' => $vars['entity']->num_display,
));
if (!$list) {
	$list = '<p class="mtm">' . elgg_echo('reportedcontent:none') . '</p>';
}

echo $list;