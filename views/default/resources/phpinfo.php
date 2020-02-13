<?php
/**
 * Show phpinfo page
 */

use Elgg\Exceptions\Http\PageNotFoundException;

if (elgg_get_config('allow_phpinfo') !== true) {
	// page is not allowed in elgg-config/settings.php
	throw new PageNotFoundException();
}

phpinfo();
