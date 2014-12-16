<?php
/**
 * Elgg single tag output. Accepts all output/url options
 *
 * @uses $vars['value']    String
 * @uses $vars['type']     The entity type, optional
 * @uses $vars['subtype']  The entity subtype, optional
 * @uses $vars['base_url'] Base URL for tag link, optional, defaults to search URL
 *
 */

if (empty($vars['value']) && $vars['value'] !== 0 && $vars['value'] !== '0') {
	return;
}

$query_params = array();

$query_params["q"] = $vars['value'];
$query_params["search_type"] = "tags";

if (!empty($vars['type'])) {
	$query_params["type"] = $vars['type'];
	unset($vars['type']);
}

if (!empty($vars['subtype'])) {
	$query_params["subtype"] = $vars['subtype'];
	unset($vars['subtype']);
}

$url = !empty($vars['base_url']) ? $vars['base_url'] : 'search';
unset($vars['base_url']);

$url .= '?' . http_build_query($query_params);

$params = array(
	'href' => $url,
	'text' => $vars['value'],
	'encode_text' => true,
	'rel' => 'tag',
);

$params = $params + $vars;

echo elgg_view('output/url', $params);
