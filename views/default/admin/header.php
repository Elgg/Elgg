<?php
/**
 * Elgg admin header
 */
?>

<h1 class="elgg-heading-site">
	<a href="<?= elgg_get_site_url(); ?>admin">
		<?= elgg_get_site_entity()->name ?> <small><?= elgg_echo('admin') ?></small>
	</a>
</h1>

<a class="elgg-admin-button-nav" rel="toggle" href="#elgg-admin-nav-collapse">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</a>

<?php echo elgg_view_menu('admin_header', ['sort_by' => 'priority']); ?>
