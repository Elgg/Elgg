<?php
/**
 * Elgg admin header
 */
 
$admin_title = elgg_get_site_entity()->name . ' ' . elgg_echo('admin');

$options = array(
	'sort_by' => 'priority'
);
echo elgg_view_menu('admin_header', $options);

?>

<h1 class="elgg-heading-site">
	<a href="<?php echo elgg_get_site_url(); ?>admin">
		<?php echo $admin_title; ?>
	</a>
</h1>