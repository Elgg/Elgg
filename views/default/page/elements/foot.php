<?php

echo elgg_format_element('script', [], elgg_view('initialize_elgg.js', $vars));

$js = elgg_get_loaded_external_resources('js', 'footer');
foreach ($js as $resource) {
	$options = [
		'src' => $resource->url,
	];
	
	if (!empty($resource->integrity)) {
		$options['integrity'] = $resource->integrity;
		$options['crossorigin'] = 'anonymous';
	}
	
	echo elgg_format_element('script', $options);
}

$deps = _elgg_services()->amdConfig->getDependencies();

echo elgg_format_element('script', [], 'require(' . json_encode($deps, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . ');');

echo elgg_view_url('#top', elgg_view_icon('chevron-up'), [
	'id' => 'elgg-scroll-to-top',
	'title' => elgg_echo('scroll_to_top'),
]);
