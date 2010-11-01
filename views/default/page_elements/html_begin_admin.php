<?php
/**
* Start html output.
* The standard HTML header for admin pages
*/

// we won't trust server configuration but specify utf-8
header('Content-type: text/html; charset=utf-8');

$version = get_version();
$release = get_version(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $vars['config']->sitename; echo " ".elgg_echo('admin'); ?></title>
	<link rel="shortcut icon" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />

	<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery-ui-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>_css/js.php?lastcache=<?php echo $vars['config']->lastcache; ?>&amp;js=initialise_elgg&amp;viewtype=<?php echo $vars['view']; ?>"></script>

	<?php
		echo elgg_view('scripts/initialize_elgg');
		echo $feedref;
		
		if (elgg_view_exists('metatags')) {
			echo elgg_view('metatags', $vars);
		}
?>
	<!-- include the admin css file 
	<link rel="stylesheet" href="<?php echo elgg_get_site_url(); ?>views/default/css_admin.php" type="text/css" />-->
</head>

<body>
