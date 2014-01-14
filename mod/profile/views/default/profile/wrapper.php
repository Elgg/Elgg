<?php
/**
 * Profile info box
 */

?>

<?php /* We add mrn here because we're doing stupid things with the grid system. Remove this hack */ ?>
<div class="profile elgg-col-2of3 mrn">
	<div class="elgg-inner clearfix h-card vcard">
		<?php echo elgg_view('profile/owner_block'); ?>
		<?php echo elgg_view('profile/details'); ?>
	</div>
</div>