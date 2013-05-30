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

?>
<div class="<?php echo $class; ?>">
	<div class="elgg-body elgg-main">
		<?php echo elgg_view("page/layouts/content/body", $vars); ?>
	</div>
</div>