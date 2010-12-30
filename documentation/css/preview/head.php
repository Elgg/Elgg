<?php
/**
 * head of the preview pages
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

$ipsum = "Sed scelerisque sagittis lorem. Phasellus sodales. Nulla urna justo, vehicula in, suscipit nec, molestie sed, tellus.";

$screen = elgg_view_get_simplecache_url('css', 'screen');
$ie_url = elgg_view_get_simplecache_url('css', 'ie');
$ie6_url = elgg_view_get_simplecache_url('css', 'ie6');

$base = elgg_get_site_url();
elgg_register_js("{$base}vendors/jquery/jquery-1.4.2.min.js", 'jquery');
elgg_register_js("{$base}vendors/jquery/jquery-ui-1.7.2.min.js", 'jquery-ui');
elgg_register_js("{$base}vendors/jquery/jquery.form.js", 'jquery.form');

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo $screen; ?>" type="text/css" />
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie_url; ?>" />
	<![endif]-->

	<!--[if gt IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie6_url; ?>" />
	<![endif]-->
<?php
foreach (elgg_get_js() as $script) {
?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php
}
?>
</head>
