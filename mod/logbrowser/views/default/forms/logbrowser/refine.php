<?php
/**
 * Form body for refining the log browser search.
 * Look for a particular person or in a time window.
 *
 * @uses $vars['user_guid']
 * @uses $vars['timelower']
 * @uses $vars['timeupper']
 */

if (isset($vars['timelower'])) {
	$lowerval = date('r',$vars['timelower']);
} else {
	$lowerval = "";
}
if (isset($vars['timeupper'])) {
	$upperval = date('r',$vars['timeupper']);
} else {
	$upperval = "";
}
if (isset($vars['user_guid'])) {
	if ($user = get_entity($vars['user_guid'])) {
		$userval = $user->username;
	}
} else {
	$userval = "";
}


$form = "<div>" . elgg_echo('logbrowser:user');
$form .= elgg_view('input/text', array(
	'name' => 'search_username',
	'value' => $userval,
)) . "</div>";

$form .= "<div>" . elgg_echo('logbrowser:starttime');
$form .= elgg_view('input/text', array(
	'name' => 'timelower',
	'value' => $lowerval,
)) . "</div>";

$form .= "<div>" . elgg_echo('logbrowser:endtime');
$form .= elgg_view('input/text', array(
	'name' => 'timeupper',
	'value' => $upperval,
))  . "</div>";
$form .= '<div class="elgg-foot">';
$form .= elgg_view('input/submit', array(
	'value' => elgg_echo('search'),
));
$form .= '</div>';

echo $form;
