<?php
/**
 * Elgg admin header
 */
$admin_title = elgg_get_site_entity()->name . ' ' . elgg_echo('admin');

$view_site = elgg_view('output/url', array(
	'href' => elgg_get_site_url(),
	'text' => elgg_echo('admin:view_site'),
	'is_trusted' => true,
));

if (elgg_get_config('elgg_maintenance_mode', null)) {
	$view_site .= ' ('
		. elgg_view('output/url', array(
			'href' => 'admin/administer_utilities/maintenance',
			'text' => elgg_echo('admin:administer_utilities:maintenance'),
			'class' => 'elgg-maintenance-mode-warning',
		))
		. ')';
}

$logout = elgg_view('output/url', array(
	'href' => 'action/logout',
	'text' => elgg_echo('logout'),
	'is_trusted' => true,
));
?>
<h1 class="elgg-heading-site">
	<a href="<?php echo elgg_get_site_url(); ?>admin">
		<?php echo $admin_title; ?>
	</a>
</h1>
<ul class="elgg-menu-user">
	<li><?php echo elgg_echo('admin:loggedin', array(elgg_get_logged_in_user_entity()->name)); ?></li>
	<li><?php echo $view_site; ?></li>
	<li><?php echo $logout; ?></li>
</ul>