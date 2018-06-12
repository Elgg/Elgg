<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$private_settings = $entity->getAllPrivateSettings();
if (empty($private_settings)) {
	$private_settings_info = elgg_echo('notfound');
} else {
	$private_settings_info = '<table class="elgg-table">';
	$private_settings_info .= '<thead><tr>';
	$private_settings_info .= '<th>key</th><th>value</th><th>&nbsp;</th>';
	$private_settings_info .= '</tr></thead>';
	
	foreach ($private_settings as $key => $value) {
		$key_val = elgg_view('output/text', ['value' => $key]);
		$value = elgg_view('output/text', ['value' => $value]);
		
		$private_settings_info .= '<tr>';
		$private_settings_info .= "<td>$key_val</td><td>$value</td>";
		$private_settings_info .= '<td>' . elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'private_setting',
				'key' => $key,
			]),
			'confirm' => true,
		]) . '</td>';
		$private_settings_info .= '</tr>';
	}
	$private_settings_info .= '</table>';
}
echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:private_settings'), $private_settings_info);
