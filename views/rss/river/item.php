<?php
/**
 * RSS river view
 *
 * @uses $vars['item']
 */
$item = $vars['item'];

$name = $item->getSubjectEntity()->name;
$name = htmlspecialchars($name, ENT_NOQUOTES, 'UTF-8');
$title = elgg_echo('river:update', array($name));

$timestamp = date('r', $item->getPostedTime());
$body = elgg_view('river/elements/summary', $vars, false, false, 'default');


$object = $item->getObjectEntity();
if ($object) {
	$url = htmlspecialchars($object->getURL());
} else {
	$url = elgg_normalize_url('activity');
}

$html = <<<__HTML
<item>
	<guid>$item->id</guid>
	<pubDate>$timestamp</pubDate>
	<link>$url</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[$body]]></description>
</item>

__HTML;

echo $html;
