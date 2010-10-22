<?php
/**
 * Elgg RSS view for a generic comment
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = get_entity($vars['annotation']->entity_guid);

$title = substr($vars['annotation']->value, 0, 32);
if (strlen($vars['annotation']->value) > 32) {
	$title .= " ...";
}

$permalink = $entity->getURL();
$pubdate = date('r', $entity->time_created);

$creator = elgg_view('object/creator', array('entity' => $entity));
$georss = elgg_view('object/georss', array('entity' => $entity));
$extensions = elgg_view('extensions/item');

$item = <<<__HTML
<item>
	<guid isPermaLink='true'>$permalink#{$vars['annotation']->id}</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink#{$vars['annotation']->id}</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[{$vars['annotation']->value}]]></description>
	$creator$georss$extensions
</item>
__HTML;

echo $item;
