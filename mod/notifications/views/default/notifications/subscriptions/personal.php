<?php
/**
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = elgg_extract('user', $vars);

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

$top_row = elgg_format_element('td', [], '&nbsp;');
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	if ($i > 0) {
		$top_row .= elgg_format_element('td', ['class' => 'spacercolumn'], '&nbsp;');
	}

	$top_row .= elgg_format_element([
		'#tag_name' => 'td',
		'class' => "{$method}togglefield",
		'#text' => elgg_echo("notification:method:{$method}"),
	]);
	$i++;
}
$top_row .= elgg_format_element('td', [], '&nbsp;');

$table_data = elgg_format_element('tr', [], $top_row);

$fields = '';
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$checked = false;
	if ($notification_settings = get_user_notification_settings($user->guid)) {
		if (isset($notification_settings->$method) && $notification_settings->$method) {
			$checked = true;
		}
	}
	
	if ($i > 0) {
		$fields .= elgg_format_element('td', ['class' => 'spacercolumn'], '&nbsp;');
	}
		
	$toggle_input = elgg_view('input/checkbox', [
		'name' => "{$method}personal",
		'id' => "{$method}checkbox",
		'value' => '1',
		'checked' => $checked,
		'onclick' => "adjust{$method}('{$method}personal');",
		'default' => false,
	]);
	$toggle_link = elgg_view('output/url', [
		'href' => false,
		'text' => $toggle_input,
		'id' => "{$method}personal",
		'class' => "{$method}toggleOff",
		'border' => '0',
		'onclick' => "adjust{$method}_alt('{$method}personal');",
	]);
		
	$fields .= elgg_format_element('td', ['class' => "{$method}togglefield"], $toggle_link);
	$i++;
}

$personal_row = elgg_format_element([
	'#tag_name' => 'td',
	'class' => 'namefield',
	'#text' => "<p>" . elgg_echo('notifications:subscriptions:personal:description') . "</p>",
]);
$personal_row .= $fields;
$personal_row .= elgg_format_element('td', [], '&nbsp;');

$table_data .= elgg_format_element('tr', [], $personal_row);

$table_attributes = [
	'id' => 'notificationstable',
	'cellspacing' => '0',
	'cellpadding' => '4',
	'width' => '100%',
];

$body = elgg_view_module('info', elgg_echo('notifications:subscriptions:personal:title'), '');
$body .= elgg_format_element('table', $table_attributes, $table_data);

echo elgg_format_element('div', ['class' => 'notification_personal'], $body);
