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

// don't use longtext because it filters output.
// that's annoying.
echo '<div class="elgg-output">';
foreach ($sections as $section => $strings) {
	if ($section === 'registration') {
		if (!elgg_get_config('allow_registration')) {
			// registration is disabled, tell the admin
			echo elgg_view_message('warning', elgg_echo("admin:widget:admin_welcome:{$section}", $strings));
		}
		
		continue;
	}
	
	echo '<p>' . elgg_echo("admin:widget:admin_welcome:{$section}", $strings) . '</p>';
}
echo '</div>';
