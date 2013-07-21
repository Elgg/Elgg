<?php
/**
 * Elgg 2 sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content']     The content string for the main column
 * @uses $vars['sidebar']     Optional content that is displayed in the sidebar
 * @uses $vars['sidebar_alt'] Optional content that is displayed in the alternate sidebar
 * @uses $vars['nav']         Optional override of the page nav (default: breadcrumbs)
 * @uses $vars['title']       Optional title for main content area
 * @uses $vars['header']      Optional override for the header
 * @uses $vars['footer']      Optional footer
 * @uses $vars['class']       Additional class to apply to layout
 */

$class = 'elgg-layout elgg-layout-two-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
?>

<div class="<?php echo $class; ?>">
	<div class="elgg-sidebar">
		<?php
			echo elgg_view('page/elements/sidebar', $vars);
		?>
	</div>
	<div class="elgg-sidebar-alt">
		<?php
			echo elgg_view('page/elements/sidebar_alt', $vars);
		?>
	</div>

	<div class="elgg-main elgg-body">
		<?php
			echo elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

			echo elgg_view('page/layouts/elements/header', $vars);

			echo $vars['content'];

			// @deprecated 1.8
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}

			echo elgg_view('page/layouts/elements/footer', $vars);
		?>
	</div>
</div>
