<?php
/**
 * Elgg admin header
 */
 
$admin_title = elgg_get_site_entity()->name . ' ' . elgg_echo('admin');

$options = array(
	'sort_by' => 'priority'
);

?>

<h1 class="elgg-heading-site">
	<a href="<?php echo elgg_get_site_url(); ?>admin">
		<?php echo $admin_title; ?>
	</a>
</h1>

<a class="elgg-admin-button-nav" rel="toggle" href="#elgg-admin-nav-collapse">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</a>

<?php echo elgg_view_menu('admin_header', $options); ?>