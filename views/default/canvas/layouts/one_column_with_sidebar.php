<?php
/**
 * Elgg 1 column with sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] The content string for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 */
?>
<div id="elgg_content" class="clearfix sidebar">
	<div id="elgg_sidebar">
		<?php
			echo elgg_view('page_elements/sidebar', $vars);
		?>
	</div>
	
	<div id="elgg_page_contents" class="clearfix">
		<?php
			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
</div>
