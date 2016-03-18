<?php
/**
 * Welcome widget for admins
 */

$sections = ['intro', 'admin_overview',	'outro'];

// don't use longtext because it filters output.
// that's annoying.
$output = '';
foreach ($sections as $section) {
	$output .= elgg_format_element('p', [], elgg_echo("admin:widget:admin_welcome:$section", []));
}
echo elgg_format_element('div', ['class' => 'elgg-output'], $output);
