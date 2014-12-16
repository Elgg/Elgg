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

$timestamp = date('r', $item->getTimePosted());
$summary = elgg_view('river/elements/summary', $vars, false, false, 'default');
$body = elgg_extract('summary', $vars, $summary);


$object = $item->getObjectEntity();
if ($object) {
	$url = htmlspecialchars($object->getURL());
} else {
	$url = elgg_normalize_url('activity');
}

$site_url = parse_url(elgg_get_site_url());
$domain = htmlspecialchars($site_url['host'], ENT_NOQUOTES, 'UTF-8');
$path = '';
if ($site_url['path']) {
	$path = htmlspecialchars($site_url['path'], ENT_NOQUOTES, 'UTF-8');
	$path = "::$path";
}

$html = <<<__HTML
	<guid isPermaLink="false">$domain$path::river::$item->id</guid>
	<pubDate>$timestamp</pubDate>
	<link>$url</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[$body]]></description>
__HTML;

echo $html;
