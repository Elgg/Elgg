<?php
/**
 * Welcome widget for admins
 */

// section => string replacements.
$sections = array(
	'intro' => array(),
	'admin_overview' => array(),
	'outro' => array()
);

// don't use longtext because it filters output.
// that's annoying.
echo '<div class="elgg-output">';
foreach ($sections as $section => $strings) {
	echo '<p>' . elgg_echo("admin:widget:admin_welcome:$section", $strings) . '</p>';
}
echo '</div>';