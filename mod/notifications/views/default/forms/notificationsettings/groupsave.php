<?php
/**
 * Elgg notifications groups subscription form
 *
 * @package ElggNotifications
 *
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = elgg_extract('user', $vars);

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	$subsbig[$method] = elgg_get_entities_from_relationship([
		'relationship' => 'notify' . $method,
		'relationship_guid' => $user->guid,
		'type' => 'group',
		'limit' => false,
	]);
	$tmparray = [];
	if ($subsbig[$method]) {
		foreach($subsbig[$method] as $tmpent) {
			$tmparray[] = $tmpent->guid;
		}
	}
	$subsbig[$method] = $tmparray;
}

$body = elgg_view('notifications/subscriptions/jsfuncs', $vars);
$body .= elgg_format_element('div', [], elgg_echo('notifications:subscriptions:groups:description'));

$groups = elgg_extract('groups', $vars);
if ($groups) {

	$top_row = elgg_format_element('td', [], '&nbsp;');

	$i = 0;
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		if ($i > 0) {
			$top_row .= '<td class="spacercolumn">&nbsp;</td>';
		}
		
		$top_row .= elgg_format_element([
			'#tag_name' => 'td',
			'class' => "{$method}togglefield",
			'#text' => elgg_echo("notification:method:{$method}"),
		]);
		$i++;
	}
	$top_row .= '<td>&nbsp;</td>';
	
	$table_data = elgg_format_element('tr', [], $top_row);

	foreach($groups as $group) {
		
		$fields = '';
		$i = 0;
		
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			
			if ($i > 0) {
				$fields .= '<td class="spacercolumn">&nbsp;</td>';
			}
			
			$toggle_input = elgg_view('input/checkbox', [
				'name' => "{$method}subscriptions[]",
				'id' => "{$method}checkbox",
				'value' => $group->guid,
				'checked' => in_array($group->guid, $subsbig[$method]),
				'onclick' => "adjust{$method}('{$method}{$group->guid}');",
			]);
			$toggle_link = elgg_view('output/url', [
				'href' => false,
				'text' => $toggle_input,
				'id' => "{$method}{$group->guid}",
				'class' => "{$method}toggleOff",
				'border' => '0',
				'onclick' => "adjust{$method}_alt('{$method}{$group->guid}');",
			]);
			
			$fields .= elgg_format_element('td', ['class' => "{$method}togglefield"], $toggle_link);
			$i++;
		}
		
		$group_row = "<td class='namefield'><div>{$group->name}</div></td>";
		$group_row .= $fields;
		$group_row .= '<td>&nbsp;</td>';
	
		$table_data .= "<tr>{$group_row}</tr>";
	}

	$table_attributes = [
		'id' => 'notificationstable',
		'cellspacing' => '0',
		'cellpadding' => '4',
		'width' => '100%',
	];

	$body .= elgg_format_element('table', $table_attributes, $table_data);
}

$footer = elgg_view('input/hidden', ['name' => 'guid', 'value' => $user->guid]);
$footer .= elgg_view('input/submit', ['value' => elgg_echo('save')]);

$body .= "<div class='elgg-foot mtm'>$footer</div>";

echo elgg_view_module('info', '', $body);
