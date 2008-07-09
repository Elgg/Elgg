<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$entity = $vars['entity'];
?>
<div class="opendd_metadata">
	<div>
		<p><?php echo elgg_echo('opendd:published'); ?>: <?php echo $entity->get('opendd:published'); ?></p>
	</div>
	<div>
		<p><?php echo elgg_echo('opendd:metadata:uuid'); ?>: <a href="<?php echo $entity->get('opendd:uuid'); ?>"><?php echo $entity->get('opendd:uuid'); ?></a></p>
	</div>
	<div>
		<p><?php echo elgg_echo('opendd:metadata:entityuuid'); ?>: <a href="<?php echo $entity->get('opendd:entity_uuid'); ?>"><?php echo $entity->get('opendd:entity_uuid'); ?></a></p>
	</div>
	<div>
		<p><?php echo elgg_echo('opendd:metadata:owneruuid'); ?>: <a href="<?php echo $entity->get('opendd:owner_uuid'); ?>"><?php echo $entity->get('opendd:owner_uuid'); ?></a></p>
	</div>
	<div>
		<p><?php echo $entity->get('opendd:name'); ?> : <?php echo $entity->get('opendd:body'); ?></p>
	</div>
</div>