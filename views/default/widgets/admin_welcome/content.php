<?php
/**
 * Welcome widget for admins
 */

// section => string replacements.
$sections = array(
	'intro' => array(),
	'admin_overview' => array(),
	'common_links' => array(
			elgg_normalize_url('pg/admin/plugins/simple'),
			elgg_normalize_url('pg/admin/site/advanced'),
	),
	'external_resources' => array(),
	'outro' => array()
);

// don't use longtext because it filters output.
// that's annoying.
foreach ($sections as $section => $strings) {
	echo '<p>' . elgg_echo("admin:widget:admin_welcome:$section", $strings) . '</p>';
}