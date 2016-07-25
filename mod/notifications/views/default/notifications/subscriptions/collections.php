<?php
/**
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = elgg_extract('user', $vars);

//@todo JS 1.8: no ?>
<script>
	function setCollection(members, method, id) {
		for ( var i in members ) {
			var checked = $('#' + method + 'collections' + id).children("INPUT[type='checkbox']").prop('checked');
			if ($("#"+method+members[i]).children("INPUT[type='checkbox']").prop('checked') != checked) {
				$("#"+method+members[i]).children("INPUT[type='checkbox']").prop('checked', checked);
				functioncall = 'adjust' + method + '_alt("'+method+members[i]+'");';
				eval(functioncall);
			}
		}
	}
</script>

<?php

$top_row = '<td>&nbsp;</td>';

$i = 0;
$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	if ($i > 0) {
		$top_row .= '<td class="spacercolumn">&nbsp;</td>';
	}

	$top_row .= elgg_format_element([
		'#tag_name' => 'td',
		'#text' => elgg_echo("notification:method:{$method}"),
		'class' => "{$method}togglefield",
	]);
	$i++;
}

$top_row .= '<td>&nbsp;</td>';

$table_data = "<tr>$top_row</tr>";

$members = [];
$friends = $user->getFriends(['limit' => 0]);
if ($friends) {
	foreach ($friends as $friend) {
		$members[] = $friend->guid;
	}
}
$memberno = sizeof($members);
$members = implode(',',$members);

$fields = '';
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$checked = false;
	
	$metaname = 'collections_notifications_preferences_' . $method;
	if ($collections_preferences = $user->$metaname) {
		if (!empty($collections_preferences) && !is_array($collections_preferences)) {
			$collections_preferences = array($collections_preferences);
		}
		if (is_array($collections_preferences)) {
			if (in_array(-1,$collections_preferences)) {
				$checked = true;
			}
		}
	}
	if ($i > 0) {
		$fields .= '<td class="spacercolumn">&nbsp;</td>';
	}
	
	$toggle_input = elgg_view('input/checkbox', [
		'name' => "{$method}collections[]",
		'id' => "{$method}checkbox",
		'value' => '-1',
		'checked' => $checked,
		'onclick' => "adjust{$method}('{$method}collections-1');",
		'default' => false,
	]);
	$toggle_link = elgg_view('output/url', [
		'href' => false,
		'text' => $toggle_input,
		'id' => "{$method}collections-1",
		'class' => "{$method}toggleOff",
		'border' => '0',
		'onclick' => "adjust{$method}_alt('{$method}collections-1'); setCollection([{$members}],'{$method}',-1);",
	]);
	
	$fields .= elgg_format_element('td', ['class' => "{$method}togglefield"], $toggle_link);
	
	$i++;
}

$friends_title = elgg_echo('friends:all') . " ({$memberno})";

$friends_row = "<td class='namefield'><p>{$friends_title}</p></td>";
$friends_row .= $fields;
$friends_row .= '<td class="spacercolumn">&nbsp;</td>';

$table_data .= "<tr>{$friends_row}</tr>";
	
$collections = get_user_access_collections($user->guid);
if ($collections) {
	foreach ($collections as $collection) {
		$members = get_members_of_access_collection($collection->id, true);
		$memberno = 0;
		if ($members) {
			$memberno = sizeof($members);
			$members = implode(',', $members);
		} else {
			$members = '';
		}

		$fields = '';
		$i = 0;
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			$metaname = 'collections_notifications_preferences_' . $method;
			$checked = false;
			if ($collections_preferences = $user->$metaname) {
				if (!empty($collections_preferences) && !is_array($collections_preferences)) {
					$collections_preferences = array($collections_preferences);
				}
				if (is_array($collections_preferences)) {
					if (in_array($collection->id,$collections_preferences)) {
						$checked = true;
					}
				}
			}
			if ($i > 0) {
				$fields .= '<td class="spacercolumn">&nbsp;</td>';
			}
			
			$toggle_input = elgg_view('input/checkbox', [
				'name' => "{$method}collections[]",
				'id' => "{$method}checkbox",
				'value' => "{$collection->id}",
				'checked' => $checked,
				'onclick' => "adjust{$method}('{$method}collections{$collection->id}');",
				'default' => false,
			]);
			$toggle_link = elgg_view('output/url', [
				'href' => false,
				'text' => $toggle_input,
				'id' => "{$method}collections{$collection->id}",
				'class' => "{$method}toggleOff",
				'border' => '0',
				'onclick' => "adjust{$method}_alt('{$method}collections{$collection->id}'); setCollection([{$members}],'{$method}',{$collection->id});",
			]);
			
			$fields .= elgg_format_element('td', ['class' => "{$method}togglefield"], $toggle_link);
			$i++;
		}
			
		$collection_title = $collection->name . " ({$memberno})";
		
		$collection_row = "<td class='namefield'><p>{$collection_title}</p></td>";
		$collection_row .= $fields;
		$collection_row .= '<td>&nbsp;</td>';
		
		$table_data .= "<tr>{$collection_row}</tr>";
	}
}

$table_attributes = [
	'id' => 'notificationstable',
	'cellspacing' => '0',
	'cellpadding' => '4',
	'width' => '100%',
];

$body = elgg_format_element([
	'#tag_name' => 'p',
	'class' => 'margin-none',
	'#text' => elgg_echo('notifications:subscriptions:friends:description'),
]);
$body .= elgg_format_element('table', $table_attributes, $table_data);

echo elgg_view_module('info', elgg_echo('notifications:subscriptions:friends:title'), $body);
