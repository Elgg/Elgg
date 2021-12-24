<?php
/**
 * Handle requests for /favicon.ico
 */

elgg_set_http_header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime('+1 week')), true);
elgg_set_http_header('Pragma: public', true);
elgg_set_http_header('Cache-Control: public', true);

$site = elgg_get_site_entity();
if ($site->hasIcon('icon-32', 'favicon')) {
	elgg_set_http_header('Content-Type: image/jpeg');
	
	echo $site->getIcon('icon-32', 'favicon')->grabFile();
	
	return;
}

elgg_set_http_header('Content-Type: image/x-icon');

echo elgg_view('graphics/favicon.ico');
