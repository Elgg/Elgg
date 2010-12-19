<?php
/**
 * Profile info box
 */

?>
<div class="profile elgg-col-2of3">
	<div class="elgg-inner clearfix">
<?php
	echo elgg_view('profile/sidebar', array('section' => 'details'));
	echo elgg_view('profile/details', array('entity' => elgg_get_page_owner()));
?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true));
	//$(selector).each(function() {
	//	if ($(this).height() > maxHeight) {
	//		maxHeight = $(this).height();
	//	}
	//})
	//$(selector).css('min-height', maxHeight);
	});
</script>