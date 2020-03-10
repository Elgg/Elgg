<?php

elgg_admin_gatekeeper();

elgg_push_context('admin');
elgg_push_context('developers_gear');

// requires "admin" and "developers_gear" in context
$menu = elgg_view_menu('page', [
	'show_section_headers' => true,
	'class' => 'elgg-developers-gear',
	'prepare_vertical' => true,
]);

$settings_form = elgg_view('admin/developers/settings');

elgg_pop_context();
elgg_pop_context();

$form_heading = elgg_echo('menu:page:header:develop') . ": " . elgg_echo('admin:developers:settings');

?>
<div class='developers-gear-popup'>
	<?php echo $menu; ?>

	<section class="developers-form">
		<h2><?php echo $form_heading; ?></h2>
		<?php echo $settings_form; ?>
	</section>
</div>
