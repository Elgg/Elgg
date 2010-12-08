<?php
/**
 * Elgg main column with one sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body']    Content HTML for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 */
?>

<div class="elgg-layout-sidebar elgg-center elgg-width-classic clearfix">
	<div class="elgg-sidebar elgg-aside">
		<?php
			echo elgg_view('layout/elements/sidebar', $vars);
		?>
	</div>

	<div class="elgg-main elgg-body">
		<?php
			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			if (isset($vars['body'])) {
				echo $vars['body'];
			}
		?>
	</div>
</div>
