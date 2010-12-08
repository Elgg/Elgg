<?php
/**
 * Elgg 2 sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] The content string for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 * @uses $vars['sidebar-alt'] Optional content that is displayed in the alternate sidebar
 */
?>

<div class="elgg-layout-two-sidebar elgg-center elgg-width-classic clearfix">
	<div class="elgg-sidebar elgg-aside">
		<?php
			echo elgg_view('layout/elements/sidebar', $vars);
		?>
	</div>
	<div class="elgg-sidebar-alt elgg-aside">
		<?php
			//$params = $vars;
			//$params['sidebar'] = $vars['sidebar-alt'];
			$params = array(
				'sidebar' => elgg_view('layout/objects/module', array('title' => 'Testing', 'body' => 'Hello, world!'))
			);
			echo elgg_view('layout/elements/sidebar', $params);
		?>
	</div>

	<div class="elgg-main elgg-body">
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
