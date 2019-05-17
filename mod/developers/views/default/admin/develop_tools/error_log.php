<?php

$dir = elgg_get_config('dataroot') . 'logs/html/';

if (!is_dir($dir)) {
	echo elgg_format_element('p', [
		'class' => 'elgg-no-results',
	], elgg_echo('developers:logs:empty'));
	return;
}

$handle = opendir($dir);
if (!$handle) {
	return;
}

while ($entry = readdir($handle)) {
	if ($entry[0] === '.') {
		continue;
	}

	$path = "$dir/$entry";

	/* @todo Parse DOM document and reverse the order of nodes so that the error log read most recent to oldest */
	$output = file_get_contents($path);

	preg_match('/errors-(.*)\.html/', $entry, $matches);

	echo elgg_view_module('aside', $matches[1], nl2br($output), [
		'class' => 'developers-error-log-module',
	]);
}
