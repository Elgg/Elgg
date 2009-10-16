<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
global $jsonexport;
if (isset($vars['items']) && is_array($vars['items'])) {

	$i = 0;
	if (!empty($vars['items'])) {
		foreach($vars['items'] as $item) {

			// echo elgg_view_river_item($item);
			if (elgg_view_exists($item->view,'default')) {
				$body = elgg_view($item->view,array('item' => $item), false, false, 'default');
				$time = date("r",$item->posted);
				if ($entity = get_entity($item->object_guid)) {
					$url = htmlspecialchars($entity->getURL());
				} else {
					$url = $vars['url'];
				}
				$title = strip_tags($body);

				$jsonitem = $item;
				$jsonitem->url = $url;
				$jsonitem->description = autop($body);
				$jsonitem->title = $title;
				unset($jsonitem->view);

				if ($subject = get_entity($item->subject_guid)) {
					elgg_view_entity($subject);
				}
				if ($object = get_entity($item->object_guid)) {
					elgg_view_entity($object);
				}

				$jsonexport['activity'][] = $jsonitem;
			}

			$i++;
			if ($i >= $vars['limit']) {
				break;
			}
		}
	}
}
echo "!";