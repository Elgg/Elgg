<?php
/**
 * The standard HTML head
 *
 * @uses $vars['title'] The page title
 * @uses $vars['meta']  Array of meta elements
 * @uses $vars['link']  Array of links
 */

// @todo why are we doing this?
// Deps are loaded in page/elements/foot with require([...])
$amdConfig = _elgg_services()->amdConfig->getConfig();
unset($amdConfig['deps']);

echo elgg_view_html_element('title', array(), $vars['title']);
foreach ($vars['meta'] as $attributes) {
	echo elgg_view_html_element('meta', $attributes);
}
foreach ($vars['link'] as $attributes) {
	echo elgg_view_html_element('link', $attributes);
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
	echo elgg_view_html_element('link', array('rel' => 'stylesheet', 'href' => $url));
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
	echo elgg_view_html_element('script', array('src' => $url));
}

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}
