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
<div class="entity_listing clearfloat">
	<div class="entity_listing_icon">
		<?php echo $vars['icon']; ?>
	</div>
	<div class="entity_listing_info">
		<?php echo $vars['info']; ?>
	</div>
</div>
