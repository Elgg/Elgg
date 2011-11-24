<?php
/**
 * List replies RSS view
 *
 * @uses $vars['entity'] ElggEntity
 */

$options = array(
	'guid' => $vars['entity']->getGUID(),
	'annotation_name' => 'group_topic_post',
);
echo elgg_list_annotations($options);
