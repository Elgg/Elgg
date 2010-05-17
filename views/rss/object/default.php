<?php
/**
 * Elgg default object view
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$title = $vars['entity']->title;
if (empty($title)) {
	$subtitle = strip_tags($vars['entity']->description);
	$title = substr($subtitle, 0, 32);
	if (strlen($subtitle) > 32) {
		$title .= ' ...';
	}
}

$permalink = htmlspecialchars($vars['entity']->getURL());
$pubdate = date('r', $vars['entity']->time_created);

$creator = '';
if ($owner = $vars['entity']->getOwnerEntity()) {
	$creator = "<dc:creator>{$owner->name}</dc:creator>";
}

$georss = '';
if (
	($vars['entity'] instanceof Locatable) &&
	($vars['entity']->getLongitude()) &&
	($vars['entity']->getLatitude())
) {
	$latitude = $vars['entity']->getLatitude();
	$longitude = $vars['entity']->getLongitude();
	$georss = "<georss:point>$latitude $longitude</georss:point>";
}

$extension = elgg_view('extensions/item');

$item = <<<__HTML
<item>
	<guid isPermaLink="true">$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[{$vars['entity']->description}]]></description>
	$creator$georss$extension
</item>

__HTML;

echo $item;
