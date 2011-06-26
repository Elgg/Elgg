<?php
/**
 * Elgg one-column layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['class']   Additional class to apply to layout
 */

$class = 'elgg-layout elgg-layout-one-column clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

?>
<div class="<?php echo $class; ?>">
	<div class="elgg-body elgg-main">
	<?php
		echo $nav;

		if (isset($vars['title'])) {
			echo elgg_view_title($vars['title']);
		}

		echo $vars['content'];
		
		// @deprecated 1.8
		echo $vars['area1'];
	?>
	</div>
</div>