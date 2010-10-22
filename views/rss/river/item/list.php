<?php
/**
 * @package Elgg
 * @subpackage Core
 */

if (isset($vars['items']) && is_array($vars['items']) && !empty($vars['items'])) {
	$i = 1;
	foreach($vars['items'] as $item) {
		if ($i++ >= $vars['limit']) {
			break;
		}
		
		$entity = get_entity($item->object_guid);
		echo elgg_view_entity($entity);
	}
}
