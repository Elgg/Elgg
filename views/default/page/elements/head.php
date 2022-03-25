<?php
/**
 * The HTML head
 *
 * @internal It's dangerous to alter this view.
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

echo elgg_format_element('title', [], elgg_extract('title', $vars), ['encode_text' => true]);

$metas = elgg_extract('metas', $vars, []);
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}

$links = elgg_extract('links', $vars, []);
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$js_foot = elgg_get_loaded_external_files('js', 'footer');
foreach ($js_foot as $url) {
	echo elgg_format_element('link', ['rel' => 'preload', 'as' => 'script', 'href' => $url]);
}

$stylesheets = elgg_get_loaded_external_files('css', 'head');
foreach ($stylesheets as $url) {
	echo elgg_format_element('link', ['rel' => 'stylesheet', 'href' => $url]);
}

$js_head = elgg_get_loaded_external_files('js', 'head');
foreach ($js_head as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

// A non-empty script *must* come below the CSS links, otherwise Firefox will exhibit FOUC
// See https://github.com/Elgg/Elgg/issues/8328
?>
<script>
	<?php // Do not convert this to a regular function declaration. It gets redefined later. ?>
	require = function () {
		// handled in the view "elgg.js"
		_require_queue.push(arguments);
	};
	_require_queue = [];
</script>
