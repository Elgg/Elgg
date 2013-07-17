<?php

if (!get_input('site_menu')) {
	echo elgg_view('output/url', array(
		'text' => 'Show Site Menu',
		'href' => elgg_http_add_url_query_elements(current_page_url(), array('site_menu' => 1)),
		'is_trusted' => true
	));
} else {
	echo elgg_view('output/url', array(
		'text' => 'Hide Site Menu',
		'href' => elgg_http_remove_url_query_element(current_page_url(), 'site_menu'),
		'is_trusted' => true
	));
}