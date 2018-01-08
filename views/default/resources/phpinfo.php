<?php
/**
 * Show phpinfo page
 */

if (elgg_get_config('allow_phpinfo') !== true) {
	// page is not allowed in elgg-config/settings.php
	throw new \Elgg\PageNotFoundException();
}

// this page is only for admins
elgg_admin_gatekeeper();

phpinfo();
