<?php
/**
 * Elgg RSS file object view
 * 
 * @package ElggFile
 * @subpackage Core
 */
$title = $vars['entity']->title;
if (empty($title)) {
	$title = elgg_get_excerpt($vars['entity']->description, 32);
}
?>

<item>
	<guid isPermaLink='true'><?php echo $vars['entity']->getURL(); ?></guid>
	<pubDate><?php echo date("r", $vars['entity']->time_created) ?></pubDate>
	<link><?php echo $vars['entity']->getURL(); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php echo (autop($vars['entity']->description)); ?>]]></description>
	<enclosure url="<?php echo elgg_get_site_url(); ?>mod/file/download.php?file_guid=<?php echo $vars['entity']->getGUID(); ?>" length="<?php echo $vars['entity']->size(); ?>" type="<?php echo $vars['entity']->getMimeType(); ?>" />
</item>
