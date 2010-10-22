<?php
/**
 * Elgg groupforumtopic view
 *
 * @package Elgg
 * @subpackage Core
 */

$title = $vars['entity']->title;

$body = '';
$annotation = $vars['entity']->getAnnotations('group_topic_post', 1, 0, "asc");
if (count($annotation == 1)) {
	$body = $annotation[0]->value;
}
?>

<item>
<guid isPermaLink='true'><?php echo htmlspecialchars($vars['entity']->getURL()); ?></guid>
<pubDate><?php echo date("r",$vars['entity']->time_created) ?></pubDate>
<link><?php echo htmlspecialchars($vars['entity']->getURL()); ?></link>
<title><![CDATA[<?php echo $title; ?>]]></title>
<description><![CDATA[<?php echo (autop($body)); ?>]]></description>
<?php
		$owner = $vars['entity']->getOwnerEntity();
		if ($owner) {
?>
<dc:creator><?php echo $owner->name; ?></dc:creator>
<?php
		}
?>
</item>