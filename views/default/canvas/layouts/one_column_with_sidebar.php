<?php
/**
 * Elgg 1 column with sidebar canvas layout
 *
 * @package Elgg
 * @subpackage Core
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
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
		?>
	</div>
</div>
