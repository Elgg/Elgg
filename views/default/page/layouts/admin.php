<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Optional title string
 */

?>

<div class="elgg-layout elgg-layout-one-sidebar">
	<div class="elgg-sidebar clearfix">
		<?php
			echo elgg_view('admin/sidebar', $vars);
		?>
	</div>
	<div class="elgg-main elgg-body">
		<div class="elgg-head">
		<?php
			echo elgg_view_menu('title', array(
				'sort_by' => 'priority',
				'class' => 'elgg-menu-hz',
			));

			if (isset($vars['title'])) {
				echo '<div class="elgg-head clearfix">';
				echo elgg_view_title($vars['title']);
				echo '</div>';
			}
		?>
		</div>
		<?php
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
</div>