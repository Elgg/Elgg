<?php
/**
 * The standard HTML head
 *
 * @uses $vars['title'] The page title
 */

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
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

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();

$version = get_version();
$release = get_version(true);
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $title; ?></title>
	<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />

<?php foreach ($css as $link) { ?>
	<link rel="stylesheet" href="<?php echo $link; ?>" type="text/css" />
<?php } ?>

<?php
	$ie_url = elgg_get_simplecache_url('css', 'ie');
	$ie6_url = elgg_get_simplecache_url('css', 'ie6');
?>
	<!--[if gt IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie_url; ?>" />
	<![endif]-->
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie6_url; ?>" />
	<![endif]-->

<?php foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<script type="text/javascript">
	<?php echo elgg_view('js/initialize_elgg'); ?>
</script>

<?php
echo $feedref;

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}