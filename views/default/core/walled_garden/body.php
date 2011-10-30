<?php
/**
 * Walled garden body
 */

echo elgg_view('core/walled_garden/login');
echo elgg_view('core/walled_garden/lost_password');

if (elgg_get_config('allow_registration')) {
	echo elgg_view('core/walled_garden/register');
}
