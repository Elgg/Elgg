<?php
/**
 * Show the contents of the error logs created by the Developers plugin.
 */

$dir = elgg_get_data_path() . 'logs/html/';
if (!is_dir($dir)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('developers:logs:empty'),
	]);
	return;
}

$directory = new \DirectoryIterator($dir);
/* @var $file_info \SplFileInfo */
foreach ($directory as $file_info) {
	if (!$file_info->isFile()) {
		continue;
	}
	
	// get the date from the error log filename
	$matches = [];
	preg_match('/errors-(.*)\.html/', $file_info->getBasename(), $matches);
	
	/* @todo Parse DOM document and reverse the order of nodes so that the error log read most recent to oldest */
	$output = file_get_contents($file_info->getPathname());
	
	echo elgg_view_module('aside', $matches[1], nl2br($output), [
		'class' => 'developers-error-log-module',
	]);
}
