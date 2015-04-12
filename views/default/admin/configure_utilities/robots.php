<?php
/**
 * Set robots.txt
 */

if ('/' !== parse_url(elgg_get_site_url(), PHP_URL_PATH)) {
	$warning = elgg_echo('admin:robots.txt:subdir');
	echo "<div class=\"elgg-admin-notices\"><p>$warning</p></div>";
}

if (file_exists(elgg_get_root_path() . 'robots.txt')) {
	// a physical robots.txt exists, which will take precedent over the any configuration
	$warning = elgg_echo('admin:robots.txt:physical');
	echo "<div class=\"elgg-admin-notices\"><p>$warning</p></div>";
}

echo elgg_view_form('admin/site/set_robots');
