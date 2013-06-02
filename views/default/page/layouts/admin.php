<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Title string
 */

?>

<div class="elgg-layout elgg-layout-one-sidebar">
	<div class="elgg-sidebar clearfix">
		<?php
			echo elgg_view('admin/sidebar', $vars);
		?>
	</div>
	<div class="elgg-main elgg-body">
		<?php echo elgg_view("page/layouts/content/body", $vars); ?>
	</div>
</div>