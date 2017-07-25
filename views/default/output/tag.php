<?php
/**
 * Elgg single tag output. Accepts all output/url options
 *
 * @uses $vars['value']    String, text of the tag
 * @uses $vars['base_url'] Base URL for tag link, optional, usable in view var hooks
 * @uses $vars['href']     URL for tag link, optional, if left empty only text is shown
 *
 */

$value = elgg_extract('value', $vars);
if (empty($value) && $value !== 0 && $value !== '0') {
	return;
}

$href = elgg_extract('href', $vars);
$vars['rel'] = 'tag';

if ($href) {
	$vars['text'] = $value;
	$vars['encode_text'] = true;
	
	echo elgg_view('output/url', $vars);
} else {
	echo elgg_view('output/text', $vars);
}
