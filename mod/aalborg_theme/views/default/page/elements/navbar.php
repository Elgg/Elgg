<?php
/**
 * Aalborg theme navbar
 * 
 */

// drop-down login
echo elgg_view('core/account/login_dropdown');

?>

<a class="elgg-button-nav" rel="toggle" data-toggle-selector=".elgg-nav-collapse" href="#">
	<?= elgg_format_element('img', [
		'src' => elgg_get_site_url() . "mod/aalborg_theme/graphics/bars.png",
		'width' => 22,
		'height' => 12,
		'alt' => elgg_echo('menu'),
		'title' => '',
	]) ?>
</a>

<div class="elgg-nav-collapse">
	<?php echo elgg_view_menu('site'); ?>
</div>
