<?php

$file = new ElggFile();

$mapping = [
	'general' => 'general',
	'application' => 'application',
	'audio' => 'music',
	'text' => 'text',
	'video' => 'film',
	'application/excel' => 'excel',
	'application/msword' => 'word',
	'application/ogg' => 'music',
	'application/pdf' => 'pdf',
	'application/powerpoint' => 'ppt',
	'application/vnd.oasis.opendocument.text' => 'openoffice',
	'application/zip' => 'archive',
	'text/v-card' => 'vcard',
];

$sizes = ['large', 'medium', 'small', 'tiny'];

$table = '<table class="elgg-table">';
$table .= '<tr>';
$table .= '<th></th>';

foreach ($sizes as $size) {
	$table .= "<th>$size</th>";
}

$table .= '</tr>';
foreach ($mapping as $mimetype => $icon) {
	$file->mimetype = $mimetype;

	$table .= '<tr>';
	$table .= "<th>{$icon}</th>";
	foreach ($sizes as $size) {
		$table .= '<td>';
		$table .= elgg_view_entity_icon($file, $size, ['use_link' => false]);
		$table .= '</td>';
	}
	
	$table .= '</tr>';
}

$table .= '</table>';

echo elgg_view_module('info', 'Files', $table);
