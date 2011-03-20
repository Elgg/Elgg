<?php
/**
 * RSS river view
 *
 * @uses $vars['item']
 */
$item = $vars['item'];

$view = $item->getView();

$name = $item->getSubjectEntity()->name;
$body = elgg_view($item->getView(), array('item' => $item), false, false, 'default');
$body = "$name $body";

$title = strip_tags($body);
$timestamp = date('r', $item->getPostedTime());

$object = $item->getObjectEntity();
if ($object) {
	$url = htmlspecialchars($object->getURL());
} else {
	$url = elgg_get_site_url() . 'activity';
}

?>
<item>
	<guid isPermaLink='true'><?php echo $url; ?></guid>
	<pubDate><?php echo $timestamp; ?></pubDate>
	<link><?php echo $url; ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php echo ($body); ?>]]></description>
</item>
