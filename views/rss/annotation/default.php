<?php
/**
 * Elgg generic comment
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

$vars['entity'] = get_entity($vars['annotation']->entity_guid);
$title = substr($vars['annotation']->value, 0, 32);
if (strlen($vars['annotation']->value) > 32) {
	$title .= " ...";
}

?>

	<item>
	<guid isPermaLink='true'><?php echo $vars['entity']->getURL(); ?>#<?php echo $vars['annotation']->id; ?></guid>
	<pubDate><?php echo date("r",$vars['entity']->time_created) ?></pubDate>
	<link><?php echo $vars['entity']->getURL(); ?>#<?php echo $vars['annotation']->id; ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php echo (autop($vars['annotation']->value)); ?>]]></description>
	<?php
		$owner = get_entity($vars['entity']->owner);
		if ($owner) {
?>
	<dc:creator><?php echo $owner->name; ?></dc:creator>
<?php
		}
	?>
	<?php
		if (
			($vars['entity'] instanceof Locatable) &&
			($vars['entity']->getLongitude()) &&
			($vars['entity']->getLatitude())
		) {
			?>
			<georss:point><?php echo $vars['entity']->getLatitude(); ?> <?php echo $vars['entity']->getLongitude(); ?></georss:point>
			<?php
		}
	?>
	<?php echo elgg_view('extensions/item'); ?>
	</item>