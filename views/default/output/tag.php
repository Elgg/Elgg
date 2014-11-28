<?php
/**
 * Elgg single tag output
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
}

if (!empty($vars['subtype'])) {
	$query_params["subtype"] = $vars['subtype'];
}

if (!empty($vars['base_url'])) {
	$url = $vars['base_url'];
} else {
	$url = elgg_get_site_url() . "search";
}

$http_query = http_build_query($query_params);
if ($http_query) {
	$url .= "?" . $http_query;
}

echo elgg_view('output/url', array(
	'href' => $url,
	'text' => $vars['value'],
	'encode_text' => true,
	'rel' => 'tag',
));
