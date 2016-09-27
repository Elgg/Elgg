<?php
/**
 * Layout body
 *
 * @uses $vars['breadcrumbs'] An array of breadcrumbs (see navigation/breadcrumbs)
 * @uses $vars['nav']         Optional page navigation (replaces navigation/breadcrumbs)
 *
 * @uses $vars['title']       Optional title for main content area
 * @uses $vars['header']      Optional override for the header
 *
 * @uses $vars['content']     Content

 * @uses $vars['footer']      Optional footer
 */
$breadcrumbs = elgg_view('page/layouts/elements/breadcrumbs', $vars);
$header = elgg_view('page/layouts/elements/header', $vars);
$filter = elgg_view('page/layouts/elements/filter', $vars);
$content = elgg_view('page/layouts/elements/content', $vars);
$footer = elgg_view('page/layouts/elements/footer', $vars);
?>

<div class="elgg-main elgg-body elgg-layout-body clearfix">
	<?= $breadcrumbs . $header . $filter . $content . $footer ?>
</div>