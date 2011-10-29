<?php
/**
 * RSS comments view
 *
 * @uses $vars['entity']
 */

$options = array(
	'guid' => $vars['entity']->getGUID(),
	'annotation_name' => 'generic_comment',
	'order_by' => 'n_table.time_created desc',
);
echo elgg_list_annotations($options);
