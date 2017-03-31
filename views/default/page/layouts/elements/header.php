<?php
/**
 * Header for layouts
 *
 * @uses $vars['title']       Title
 * @uses $vars['header']      Optional override for the header content
 * @uses $vars['breadcrumbs'] Breadcrumbs
 * @uses $vars['extras']      Optional override of extras menu
 * @uses $vars['filter']      Optional filter tabs
 */
$header = elgg_extract('header', $vars);
unset($vars['header']);

$title = elgg_extract('title', $vars, '');
unset($vars['title']);

if (!isset($header)) {
	if ($title) {
		$title = elgg_view_title($title, [
			'class' => 'elgg-heading-main display-4',
			'tag' => 'h1',
		]);
	}

	$menu_params = $vars;
	$menu_params['sort_by'] = 'priority';
	$menu_params['class'] = 'elgg-menu-hz';
	$buttons = elgg_view_menu('title', $menu_params);

	$header = $title . $buttons;
}

if ($header === false) {
	return;
}

$nav = elgg_view('page/layouts/elements/breadcrumbs', $vars);

$extras = elgg_extract('extras', $vars);
if (!isset($extras)) {
	$extras = elgg_view_menu('extras', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}
if ($extras) {
	$nav .= $extras;
}

$filter = elgg_view('page/layouts/elements/filter', $vars);

$cover = '';
$cover_url = elgg_extract('cover_url', $vars);
if ($cover_url) {
	$cover = elgg_format_element('div', [
		'class' => 'elgg-layout-header-cover',
		'style' => "background-image: url($cover_url);",
	]);
}
?>
<div class="elgg-head elgg-layout-header clearfix">
	<?= $cover ?>
	<div class="elgg-inner container">
		<?php if ($nav) { ?>
			<div class="elgg-layout-header-nav d-flex justify-content-between flex-column flex-md-row">
				<?= $nav ?>
			</div>
		<?php } ?>

		<?php if ($header) { ?>
			<div class="elgg-layout-header-heading">
				<?= $header ?>
			</div>
		<?php } ?>

		<?php if ($filter) { ?>
			<div class="elgg-layout-header-filter">
				<?= $filter ?>
			</div>
		<?php } ?>

	</div>
</div>
