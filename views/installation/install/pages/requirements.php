<?php
/**
 * Install requirements checking page
 *
 * @uses $vars['num_failures] Number of requirements failures
 * @uses $vars['num_warnings] Number of recommendation warnings
 */

if ($vars['num_failures'] != 0) {
	$instruct_text = elgg_echo('install:requirements:instructions:failure');
	
	// cannot advance to next step with a failure
	$vars['advance'] = false;
	$vars['refresh'] = true;
} elseif ($vars['num_warnings'] != 0) {
	$vars['refresh'] = true;
	$instruct_text = elgg_echo('install:requirements:instructions:warning');
} else {
	$instruct_text = elgg_echo('install:requirements:instructions:success');
}

echo elgg_autop($instruct_text);

$report = elgg_extract('report', $vars);
foreach ($report as $category => $checks) {
	echo elgg_format_element('h3', [], elgg_echo("install:require:{$category}"));
	
	$list_items = '';
	foreach ($checks as $check) {
		$message = elgg_view_message(elgg_extract('severity', $check, 'notice'), elgg_autop($check['message']), ['icon_name' => false]);
		$list_items .= elgg_format_element('li', [], $message);
	}
	
	echo elgg_format_element('ul', ['class' => "elgg-require-{$category}"], $list_items);
}

echo elgg_view('install/nav', $vars);
