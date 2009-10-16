<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (isset($vars['items']) && is_array($vars['items'])) {

	$i = 0;
	if (!empty($vars['items'])) {
		foreach($vars['items'] as $item) {

			// echo elgg_view_river_item($item);
			if (elgg_view_exists($item->view,'default')) {
				$body = elgg_view($item->view,array('item' => $item),false,false,'default');
				$time = date("r",$item->posted);
				if ($entity = get_entity($item->object_guid)) {
					$url = htmlspecialchars($entity->getURL());
				} else {
					$url = $vars['url'];
				}
				$title = strip_tags($body);

	?>
	<item>
		<guid isPermaLink='true'><?php echo $url; ?></guid>
		<pubDate><?php echo $time; ?></pubDate>
		<link><?php echo $url; ?></link>
		<title><![CDATA[<?php echo $title; ?>]]></title>
		<description><![CDATA[<?php echo (autop($body)); ?>]]></description>
	</item>
	<?php

			}

			$i++;
			if ($i >= $vars['limit']) {
				break;
			}
		}
	}
}