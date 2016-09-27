<?php
/**
 * Layout breadcrumbs
 *
 * @uses $vars['breadcrumbs'] An array of breadcrumbs
 * @uses $vars['nav']         Optional HTML overriding the breadcrumbs entirely
 */
if (isset($vars['nav'])) {
	$breadcrumbs = $vars['nav'];
} else {
	$breadcrumbs = elgg_view('navigation/breadcrumbs', $vars);
}

if (!$breadcrumbs) {
	return;
}
?>
<div class="elgg-layout-breadcrumbs clearfix">
	<?= $breadcrumbs ?>
</div>

