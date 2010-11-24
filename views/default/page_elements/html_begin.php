<?php
/**
 * Start html output.
 * The standard HTML header that displays across the site
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 */

// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} else if (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}

global $autofeed;
if (isset($autofeed) && $autofeed == true) {
	$url = full_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$url = elgg_format_url($url);
	$feedref = <<<END

	<link rel="alternate" type="application/rss+xml" title="RSS" href="{$url}" />

END;
} else {
	$feedref = "";
}

$js = elgg_get_js('head');
$css = elgg_get_css();

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
	<title><?php echo $title; ?></title>
	<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />

<?php
foreach ($js as $script) {
?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php
}

foreach ($css as $link) {
?>
	<link rel="stylesheet" href="<?php echo $link; ?>" type="text/css" />
<?php
}
?>
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo elgg_get_site_url(); ?>views/default/css_ie6.php" />
	<![endif]-->

	<!--[if gt IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo elgg_get_site_url(); ?>views/default/css_ie.php" />
	<![endif]-->
<?php

echo elgg_view('scripts/initialize_elgg');
echo $feedref;

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated for html_head/extend", 1.8);
	echo $metatags;
}
echo elgg_view('html_head/extend', $vars);
?>
</head>

<body>
