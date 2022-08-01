<?php
/**
 * Welcome widget for admins
 */

// section => string replacements.
$sections = [
	'intro' => [],
	'registration' => [
		elgg_view('output/url', [
			'text' => elgg_echo('admin:site_settings'),
			'href' => elgg_generate_url('admin', [
				'segments' => 'site_settings',
			]),
			'is_trusted' => true,
		]),
	],
	'admin_overview' => [],
	'outro' => [],
];

$results = '';
foreach ($sections as $section => $strings) {
	if ($section === 'registration') {
		if (!elgg_get_config('allow_registration')) {
			// registration is disabled, tell the admin
			echo elgg_view_message('warning', elgg_echo("admin:widget:admin_welcome:{$section}", $strings));
		}
		
		continue;
	}
	
	$results .= elgg_format_element('p', [], elgg_echo("admin:widget:admin_welcome:{$section}", $strings));
}

echo elgg_view('output/longtext', ['value' => $results, 'sanitize' => false]);
