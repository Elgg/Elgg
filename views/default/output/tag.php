<?php
/**
 * Elgg single tag output
 *
 * @uses $vars['value']   String
 * @uses $vars['type']    The entity type, optional
 * @uses $vars['subtype'] The entity subtype, optional
 *
 */

if (!empty($vars['type'])) {
	$type = "&type=" . rawurlencode($vars['type']);
} else {
	$type = "";
}
if (!empty($vars['subtype'])) {
	$subtype = "&subtype=" . rawurlencode($vars['subtype']);
} else {
	$subtype = "";
}
if (!empty($vars['object'])) {
	$object = "&object=" . rawurlencode($vars['object']);
} else {
	$object = "";
}

if (isset($vars['value'])) {
	$url = elgg_get_site_url() . 'search?q=' . rawurlencode($vars['value']) . "&search_type=tags{$type}{$subtype}{$object}";
	$vars['value'] = htmlspecialchars($vars['value'], ENT_QUOTES, 'UTF-8', false);
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $vars['value'],
		'rel' => 'tag',
	));
}
