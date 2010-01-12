<?php
/**
 * Elgg core search.
 * Search entity view for RSS feeds.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>, The MITRE Corporation <http://www.mitre.org>
 * @link http://elgg.org/
 */

$title = $vars['entity']->title;
if (empty($title)) {
	$title = substr($vars['entity']->description, 0, 32);
	if (strlen($vars['entity']->description) > 32) {
		$title .= " ...";
	}
}

?>

<item>
<guid isPermaLink='true'><?php echo htmlspecialchars($vars['entity']->getURL()); ?></guid>
<pubDate><?php echo date("r", $vars['entity']->time_created) ?></pubDate>
<link><?php echo htmlspecialchars($vars['entity']->getURL()); ?></link>
<title><![CDATA[<?php echo $title; ?>]]></title>
<description><![CDATA[<?php
	$summary = $vars['entity']->summary;
	if (!empty($summary)) echo wpautop($summary);
	echo (autop($vars['entity']->description));
?>]]></description>
</item>
