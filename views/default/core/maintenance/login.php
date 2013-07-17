<?php
/**
 * Maintenance mode login
 */

echo elgg_view('core/account/login_box', array(
	'module' => 'maintenance-login',
	'title' => elgg_echo('admin:login'),
));
