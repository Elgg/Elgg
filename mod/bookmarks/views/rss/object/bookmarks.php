<?php
/**
 * Bookmark RSS object view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggBookmark) {
	return;
}

$title = $entity->getDisplayName();
if (empty($title)) {
	$title = strip_tags($entity->description ?: '');
	$title = elgg_get_excerpt($title, 32);
}

$permalink = htmlspecialchars($entity->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $entity->getTimeCreated());

$url_text = elgg_echo('bookmarks:address');
$link = elgg_view('output/url', ['href' => $entity->address]);
$description = $entity->description;
$description .= elgg_format_element('p', [], "{$url_text}: {$link}");

$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

?>
<item>
	<guid isPermaLink="true"><?= $permalink; ?></guid>
	<pubDate><?= $pubdate; ?></pubDate>
	<link><?= $permalink; ?></link>
	<title><![CDATA[<?= $title; ?>]]></title>
	<description><![CDATA[<?= $description; ?>]]></description>
	<?= $creator; ?>
	<?= $georss; ?>
	<?= $extension; ?>
</item>
