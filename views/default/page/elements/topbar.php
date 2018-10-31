<?php

/**
 * Topbar
 */
elgg_require_js('page/elements/topbar');
?>

<div class="elgg-nav-logo">
	<?php
	echo elgg_view('page/elements/header_logo');
	?>
</div>

<?= elgg_view('core/account/login_dropdown') ?>

<div class="elgg-nav-button">
	<span></span>
	<span></span>
	<span></span>
</div>

<div class="elgg-nav-collapse">
	<?php
	echo elgg_format_element('div', [
		'class' => 'elgg-nav-search',
	], elgg_view('search/search_box'));

	echo elgg_view_menu('site', [
		'sort_by' => 'text',
	]);
	echo elgg_view_menu('topbar');
	?>
</div>
