<?php
/**
 * Layout body
 *
 * @uses $vars['breadcrumbs']  Breadcrumbs
 *                             Will no be rendered if the value is 'false'
 *                             Will render 'navigation/breadcrumbs' view if
 *                             not set or is an array of breadcrumbs
 *                             Will override breadcrumbs view if set to a string
 *
 * @uses $vars['title']       Optional title for main content area
 * @uses $vars['header']      Optional override for the header
 *
 * @uses $vars['content']     Content

 * @uses $vars['footer']      Optional footer
 */
$filter = elgg_view('page/layouts/elements/filter', $vars);
$content = elgg_view('page/layouts/elements/content', $vars);
$footer = elgg_view('page/layouts/elements/footer', $vars);
?>

<div class="elgg-main elgg-body elgg-layout-body clearfix">
	<?= $filter . $content . $footer ?>
</div>
