<?php
/**
 * Set robots.txt
 */

if ('/' !== parse_url(elgg_get_site_url(), PHP_URL_PATH)) {
	$warning = elgg_echo('admin:robots.txt:subdir');
	echo "<div class=\"elgg-admin-notices\"><p>$warning</p></div>";
}

echo elgg_view_form('admin/site/set_robots');
