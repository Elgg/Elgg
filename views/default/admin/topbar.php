<?php
/**
 * Elgg topbar
 * The standard elgg top toolbar
 */

$menu = elgg_view_menu('admin_header', [
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
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
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
		echo elgg_view('output/url', [
			'href' => 'admin',
			'text' => elgg_echo('admin'),
			'class' => 'elgg-heading-site navbar-brand',
		]);
		;
		echo $menu;
		echo $site;
		?>
	</div>
</nav>