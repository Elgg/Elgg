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

echo elgg_format_element('title', [], (string) elgg_extract('title', $vars), ['encode_text' => true]);

$metas = elgg_extract('metas', $vars, []);
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}

$links = elgg_extract('links', $vars, []);
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

echo elgg_view('page/elements/importmap.json');

$js_foot = elgg_get_loaded_external_resources('js', 'footer');
foreach ($js_foot as $resource) {
	$options = [
		'rel' => 'preload',
		'as' => 'script',
		'href' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('link', $options);
}

$stylesheets = elgg_get_loaded_external_resources('css', 'head');
foreach ($stylesheets as $resource) {
	$options = [
		'rel' => 'stylesheet',
		'href' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('link', $options);
}

$js_head = elgg_get_loaded_external_resources('js', 'head');
foreach ($js_head as $resource) {
	$options = [
		'src' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('script', $options);
}


$imports = _elgg_services()->esm->getImports();
if (empty($imports)) {
	return;
}

?>
<script type="module">
<?php
foreach ($imports as $module) {
	echo "import '{$module}';" . PHP_EOL;
}
?>
</script>
