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

echo elgg_view_url('#top', elgg_view_icon('chevron-up'), [
	'id' => 'elgg-scroll-to-top',
	'title' => elgg_echo('scroll_to_top'),
]);
