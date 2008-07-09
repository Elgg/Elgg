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
<div class="opendd_entity">
	<div>
		<p><?php echo elgg_echo('opendd:published'); ?>: <?php echo $entity->get('opendd:published'); ?></p>
	</div>
	<div>
		<p><?php echo elgg_echo('opendd:entity:uuid'); ?>: <a href="<?php echo $entity->get('opendd:uuid'); ?>"><?php echo $entity->get('opendd:uuid'); ?></a></p>
	</div>
	<div>
		<p><?php echo elgg_echo('opendd:entity:class'); ?>: <?php echo $entity->get('opendd:class'); ?></p>
		<p><?php echo elgg_echo('opendd:entity:subclass'); ?>: <?php echo $entity->get('opendd:subclass'); ?></p>
	</div>
</div>