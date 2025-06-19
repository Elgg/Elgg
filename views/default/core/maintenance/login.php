<?php
/**
 * Maintenance mode login
 */

elgg_deprecated_notice('The view \'core\maintenance\login\' is deprecated', '6.3');

echo elgg_view('core/account/login_box', [
	'module' => 'maintenance-login',
	'title' => elgg_echo('admin:login'),
]);
