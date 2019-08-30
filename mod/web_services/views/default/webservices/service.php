<?php
/**
 * Display information about a webservice
 *
 * @uses $vars['service'] the webservice method name
 * @uses $Vars['params']  the webservice parameters
 */

$service = elgg_extract('service', $vars);
$params = elgg_extract('params', $vars);
if (empty($service) || empty($params)) {
	return;
}

$content = '';

// title
$title = $service;
$title .= elgg_format_element('span', ['class' => ['mls', 'elgg-quiet']], elgg_extract('call_method', $params));

$content .= elgg_view('object/elements/summary/title', [
	'title' => $title,
]);

// imprint
$imprint = [];

$imprint[] = elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('require_api_auth', $params) ? 'check' : 'delete',
	'content' => elgg_echo('webservices:requires_api_authentication'),
	'class' => 'mrm',
]);
$imprint[] = elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('require_user_auth', $params) ? 'check' : 'delete',
	'content' => elgg_echo('webservices:requires_user_authentication'),
	'class' => 'mrm',
]);

$content .= elgg_format_element('div', ['class' => 'webservices-service-imprint elgg-subtext'], implode(PHP_EOL, $imprint));

// description
$description = elgg_extract('description', $params);

$read_more_class = 'webservices-service-' . elgg_get_friendly_title($service);

$description .= elgg_view('output/url', [
	'text' => elgg_echo('more_info'),
	'href' => false,
	'class' => 'mlm',
	'data-toggle-Selector' => ".{$read_more_class}",
	'rel' => 'toggle',
]);

if (!elgg_is_empty($description)) {
	$content .= elgg_format_element('div', [], $description);
}

// more information (like function, params)
$read_more = '';

$function = elgg_format_element('strong', ['class' => 'mrs'], elgg_echo('webservices:function'));
$function .= elgg_extract('function', $params);

$read_more .= elgg_format_element('div', [], $function);

$service_params = elgg_extract('parameters', $params);
if (!empty($service_params)) {
	$param_output = elgg_format_element('strong', [], elgg_echo('webservices:parameters'));
	
	$param_list = '';
	foreach ($service_params as $name => $config) {
		$param = elgg_format_element('span', ['class' => ['elgg-quiet', 'mrs']], '(' . elgg_extract('type', $config) . ')');
		$param .= $name . ' - ';
		if (elgg_extract('required', $config)) {
			$param .= elgg_echo('webservices:parameters:required');
		} else {
			$param .= elgg_echo('webservices:parameters:optional');
		}
		
		$param_list .= elgg_format_element('li', [], $param);
	}
	
	$param_output .= elgg_format_element('ul', [], $param_list);
	
	$read_more .= elgg_format_element('div', ['class' => ['elgg-output']], $param_output);
}

$content .= elgg_format_element('div', ['class' => ['hidden', 'webservices-service-more', $read_more_class]], $read_more);

echo elgg_format_element('li', ['class' => ['elgg-item', 'webservices-service']], $content);
