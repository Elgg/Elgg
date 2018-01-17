<?php
/**
 * Install requirements checking page
 *
 * @uses $vars['num_failures] Number of requirements failures
 * @uses $vars['num_warnings] Number of recommendation warnings
 */

if ($vars['num_failures'] != 0) {
	$instruct_text = elgg_echo('install:requirements:instructions:failure');
} elseif ($vars['num_warnings'] != 0) {
	$instruct_text = elgg_echo('install:requirements:instructions:warning');
} else {
	$instruct_text = elgg_echo('install:requirements:instructions:success');
}

echo elgg_autop($instruct_text);

$report = elgg_extract('report', $vars);
foreach ($report as $category => $checks) {
	$title = elgg_echo("install:require:$category");
	echo "<h3>$title</h3>";
	echo "<ul class=\"elgg-require-$category\">";
	foreach ($checks as $check) {
		echo '<li>';
		echo elgg_view_message(elgg_extract('severity', $check, 'notice'), elgg_autop($check['message']));
		echo '</li>';
	}
	echo "</ul>";
}

$vars['refresh'] = true;

// cannot advance to next step with a failure
if ($vars['num_failures'] != 0) {
	$vars['advance'] = false;
}

echo elgg_view('install/nav', $vars);
