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
<div class="opendd_relationship">
	<div>
		<p><?php echo elgg_echo('opendd:published'); ?>: <?php echo $entity->get('opendd:published'); ?></p>
	</div>
	<div>
		<p>
			<a href="<?php echo $entity->get('opendd:uuid1'); ?>"><?php echo $entity->get('opendd:uuid1'); ?></a>
			<b><?php echo $entity->get('opendd:type'); ?></b>
			<a href="<?php echo $entity->get('opendd:uuid2'); ?>"><?php echo $entity->get('opendd:uuid2'); ?></a>
		</p>
	</div>
</div>