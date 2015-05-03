<?php
/**
 * The HTML head
 *
 * @internal It's dangerous to alter this view.
 * 
 * JavaScript load sequence (set in views library and this view)
 * ------------------------
 * 1. Elgg's initialization which is inline because it can change on every page load.
 * 2. RequireJS config. Must be loaded before RequireJS is loaded.
 * 3. RequireJS
 * 4. jQuery
 * 5. jQuery migrate
 * 6. jQueryUI
 * 7. elgg.js
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

$metas = elgg_extract('metas', $vars, array());
$links = elgg_extract('links', $vars, array());

echo elgg_format_element('title', array(), $vars['title'], array('encode_text' => true));
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$css = elgg_get_loaded_css();

$html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
$ie_url = elgg_get_simplecache_url('css', 'ie');

?>
	<script>
<?php // Do not convert elgg_defer to a regular function declaration. We have to redefine it later. ?>
elgg_defer = function (f) {
	// handled in the view "js/elgg/process_deferred"
	elgg_defer._queue.push(f);
};
elgg_defer._queue = [];
	</script>
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

<?php

echo elgg_view_deprecated('page/elements/shortcut_icon', array(), "Use the 'head', 'page' plugin hook.", 1.9);

echo elgg_view_deprecated('metatags', array(), "Use the 'head', 'page' plugin hook.", 1.8);
