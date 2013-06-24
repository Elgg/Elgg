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
	$title = $vars['title'] . ' : ' . elgg_get_config('sitename');
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


$amdConfig = _elgg_services()->amdConfig->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();

$html5shiv = elgg_normalize_url('vendors/html5shiv.js');
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<?php echo elgg_view('page/elements/shortcut_icon', $vars); ?>

	<!--[if lt IE 9]>
		<script src="<?php echo $html5shiv; ?>"></script>
	<![endif]-->

<?php foreach ($css as $link) { ?>
	<link rel="stylesheet" href="<?php echo $link; ?>" />
<?php } ?>

<?php
	$ie_url = elgg_get_simplecache_url('css', 'ie');
	$ie8_url = elgg_get_simplecache_url('css', 'ie8');
	$ie7_url = elgg_get_simplecache_url('css', 'ie7');
?>
	<!--[if gt IE 8]>
		<link rel="stylesheet" href="<?php echo $ie_url; ?>" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" href="<?php echo $ie8_url; ?>" />
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $ie7_url; ?>" />
	<![endif]-->

<script>
if (typeof require == "undefined") {
	var require = <?php echo json_encode($amdConfig); ?>;
}
</script>
<script><?php echo elgg_view('js/initialize_elgg'); ?></script>

<?php foreach ($js as $script) { ?>
	<script src="<?php echo $script; ?>"></script>
<?php } ?>

<?php
echo $feedref;

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}
