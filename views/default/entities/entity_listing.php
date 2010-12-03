<?php
/**
 * Generic display for a single entity in list view.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['icon'] Full icon HTML to display.
 * @uses string $vars['info'] Info about the entity.
 */
?>
<div class="listing entity-listing">
	<div class="icon">
		<?php echo $vars['icon']; ?>
	</div>
	<div class="info">
		<?php echo $vars['info']; ?>
	</div>
</div>
