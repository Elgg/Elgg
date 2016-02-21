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

$metas = elgg_extract('metas', $vars, array());
$links = elgg_extract('links', $vars, array());

echo elgg_format_element('title', array(), $vars['title'], array('encode_text' => true));
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$stylesheets = elgg_get_loaded_css();
foreach ($stylesheets as $url) {
	echo elgg_format_element('link', [
		'rel' => 'stylesheet',
		'href' => $url,
	]);
}

// A non-empty script *must* come below the CSS links, otherwise Firefox will exhibit FOUC
// See https://github.com/Elgg/Elgg/issues/8328
?>
<script>
<?php
// Do not convert this to a regular function declaration. It gets redefined later.
// This sets up a fake require() to capture usage in the BODY.
// See the page/elements/foot view.
?>
require = function () {
	_require_queue.push(arguments);
};
_require_queue = [];
</script>
