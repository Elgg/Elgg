<?php
/**
 * The HTML head
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

echo elgg_format_element('title', array(), $vars['title'], array('encode_text' => true));
foreach ($vars['metas'] as $attributes) {
	echo elgg_format_element('meta', $attributes);
}
foreach ($vars['links'] as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();
$require_config_url = elgg_get_simplecache_url('js', 'elgg/require_config');
$elgg_init = elgg_view('js/initialize_elgg');

$html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
$ie_url = elgg_get_simplecache_url('css', 'ie');
$ie8_url = elgg_get_simplecache_url('css', 'ie8');
$ie7_url = elgg_get_simplecache_url('css', 'ie7');

?>

	<!--[if lt IE 9]>
		<script src="<?php echo $html5shiv_url; ?>"></script>
	<![endif]-->

<?php

foreach ($css as $url) {
	echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
}

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

	<script src="<?php echo $require_config_url; ?>"></script>
	<script><?php echo $elgg_init; ?></script>
<?php
foreach ($js as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

$icon = elgg_view('page/elements/shortcut_icon', $vars);
if ($icon) {
	elgg_deprecated_notice("The page/elements/shortcut_icon view has been deprecated. Use the 'head', 'page' plugin hook.", 1.9);
	echo $icon;
}

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Use the 'head', 'page' plugin hook.", 1.8);
	echo $metatags;
}
