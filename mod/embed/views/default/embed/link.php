<?php
/**
 * Displays an "Embed media" link in longtext inputs.
 */

// yeah this is naughty.  embed and ecml might want to merge.
if (elgg_is_active_plugin('ecml')) {
	$active_section = 'active_section=web_services&';
} else {
	$active_section = '';
}

$url = "pg/embed/?{$active_section}internal_name={$vars['name']}";

echo elgg_view('output/url', array(
	'href' => $url,
	'text' => elgg_echo('media:insert'),
	'rel' => 'facebox',
	'class' => 'elgg-longtext-control',
));
