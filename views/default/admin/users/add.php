<?php
/**
 * Display an add user form.
 */

echo elgg_view_form('useradd', [
	'sticky_enabled' => true,
	'sticky_ignored_fields' => [
		'password',
		'password2',
	],
]);
