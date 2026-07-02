<?php
/**
 * List all registered API endpoints (methods)
 */

use Elgg\WebServices\Di\ApiRegistrationService;

$services = ApiRegistrationService::instance()->getAllApiMethods();
if (empty($services)) {
	echo elgg_view_no_results();
	return;
}

$lis = [];

foreach ($services as $service) {
	$lis[] = elgg_view('webservices/service', [
		'service' => $service,
	]);
}

echo elgg_format_element('ul', ['class' => 'elgg-list'], implode(PHP_EOL, $lis));
