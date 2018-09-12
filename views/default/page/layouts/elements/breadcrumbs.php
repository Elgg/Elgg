<?php
/**
 * Layout breadcrumbs
 *
 * @uses $vars['breadcrumbs']  Breadcrumbs
 *                             Will no be rendered if the value is 'false'
 *                             Will override breadcrumbs view if set to a string
 *                             Will render 'navigation/breadcrumbs' view if
 *                             not set or is an array of breadcrumbs
 *                             <code>
 *                             [
 *                                [
 *                                   'title' => 'Breadcrumb title',
 *                                   'href' => '/path/to/page',
 *                                ],
 *                             ]
 *                             </code>
 */
$breadcrumbs = elgg_extract('breadcrumbs', $vars);
if ($breadcrumbs === false) {
	return;
}
if (is_string($breadcrumbs)) {
	echo $breadcrumbs;
	return;
}

$breadcrumbs = elgg_view('navigation/breadcrumbs', $vars);
if (!$breadcrumbs) {
	return;
}
?>
<div class="elgg-layout-breadcrumbs clearfix">
	<?= $breadcrumbs ?>
</div>

