<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (isset($vars['items']) && is_array($vars['items']) && !empty($vars['items'])) {
	foreach($vars['items'] as $item) {
		if (elgg_view_exists($item->view)) {
			$body = elgg_view($item->view, array('item' => $item));
			$time = date('r', $item->posted);
			if ($entity = get_entity($item->object_guid)) {
				$url = htmlspecialchars($entity->getURL());
			} else {
				$url = $vars['url'];
			}
			$title = strip_tags($body);
			
			echo <<<__HTML
<item>
	<guid isPermaLink="true">$url</guid>
	<pubDate>$time</pubDate>
	<title><![CDATA[$title]]></title>
	<link>$url</link>
	<description><![CDATA[$body]]></description>
</item>

__HTML;
			
			$i++;
			if ($i >= $vars['limit']) {
				break;
			}
		}
	}
}
