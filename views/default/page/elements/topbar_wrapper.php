<?php
/**
 * Elgg topbar wrapper
 * Check if the user is logged in and display a topbar
 * @since 1.10 
 */
if (!elgg_is_logged_in()) {
	return true;
}
?>
<div class="elgg-page-topbar">
	<div class="elgg-inner">
		<?php
		echo elgg_view('page/elements/topbar', $vars);
		?>
	</div>
</div>