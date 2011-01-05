<?php
/**
 * Set default access for older sites
 */

$access = elgg_get_config('default_access');
if ($access == false) {
	elgg_save_config('default_access', ACCESS_LOGGED_IN);
}
