<?php
/**
 * Elgg topbar
 * The standard elgg top toolbar
 */

$items = [];
$site_menu = elgg()->menus->getMenu('site');
$site_menu_sections = $site_menu->getSections();

foreach ($site_menu_sections as $site_menu_items) {
	foreach ($site_menu_items as $item) {
		$item->setSection('tools');
		$items[] = $item;
	}
}

$menu = elgg_view_menu('topbar', [
	'items' => $items,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz navbar-nav',
	'#class' => [
		'collapse',
		'navbar-collapse',
		'd-md-flex',
		'justify-content-end',
		'align-items-center',
	],
	'#id' => 'elgg-topbar-nav',
		]);
?>
<nav class="navbar navbar-toggleable-md navbar-inverse bg-primary">
	<div class="container">
		<?php
		echo elgg_view('input/button', [
			'text' => elgg_view_icon('bars'),
			'class' => 'navbar-toggler navbar-toggler-right',
			'data-toggle' => 'collapse',
			'data-target' => '#elgg-topbar-nav',
			'aria-controls' => 'elgg-topbar-nav',
			'aria-expanded' => 'false',
			'aria-label' => 'Toggle navigation',
		]);
		echo elgg_view('page/elements/header_logo');
		echo $menu;
		echo $site;
		?>
	</div>
</nav>