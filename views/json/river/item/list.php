<?php
/**
 * JSON river view
 *
 * @package Elgg
 * @subpackage Core
 */
global $jsonexport;

$json_items = array();

if (isset($vars['items']) && is_array($vars['items'])) {
	$i = 0;
	
	if (!empty($vars['items'])) {
		foreach($vars['items'] as $item) {
			
			$json_entry = array(
				'subject' 		=>	NULL,
				'object' 		=>	NULL,
				'type' 			=>	NULL,
				'subtype' 		=>	NULL,
				'action_type' 	=>	NULL,
				'view' 			=>	NULL,
				'annotation'	=>	NULL,
				'timestamp' 	=>	NULL,
				'string' 		=>	NULL
			);

			if (elgg_view_exists($item->view, 'default')) {
				$json_entry['string'] = elgg_view($item->view, array('item' => $item), FALSE, FALSE, 'default');
				$json_entry['timestamp'] = (int)$item->posted;
			}

			$json_items[] = $json_entry;
			
			$i++;
			if ($i >= $vars['limit']) {
				break;
			}
		}
	}
}

$jsonexport['activity'] = $json_items;
