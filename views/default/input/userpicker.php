<?php
/**
 * Elgg user picker
 *
 * @uses $vars['only_friends']   If enabled, will turn the input into a friends picker (default: false)
 * @uses $vars['show_friends']   Show the option to limit the search to friends (default: true)
 * @uses $vars['include_banned'] Include banned users in the search results (default: false)
 */

$options = (array) elgg_extract('options', $vars, []);

$options['include_banned'] = (bool) elgg_extract('include_banned', $vars, false);
$options['friends_only'] = (bool) elgg_extract('only_friends', $vars, false);

$show_friends = (bool) elgg_extract('show_friends', $vars, true) && !$options['friends_only'];

$default_match_on = 'users';
if ($show_friends) {
	$vars['picker_extras'] = elgg_view('input/checkbox', [
		'name' => 'match_on',
		'value' => 'friends',
		'default' => elgg_extract('match_on', $vars, 'users', false),
		'label' => elgg_echo('userpicker:only_friends'),
	]);
} elseif ($options['friends_only']) {
	$default_match_on = 'friends';
}

if (!isset($vars['name'])) {
	$vars['name'] = 'members';
}

$vars['match_on'] = elgg_extract('match_on', $vars, $default_match_on);
$vars['class'] = elgg_extract_class($vars, ['elgg-user-picker']);
$vars['options'] = $options;

echo elgg_view('input/entitypicker', $vars);
