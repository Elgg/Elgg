<?php
/**
 * Profile info box
 */

?>
<div class="profile elgg-col-2of3">
	<div class="elgg-inner clearfix">
		<?php echo elgg_view('profile/owner_block'); ?>
		<?php echo elgg_view('profile/details'); ?>
	</div>
</div>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true) + 1);
	});
</script>