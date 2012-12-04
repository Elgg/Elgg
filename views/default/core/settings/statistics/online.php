<?php
/**
 * Statistics about this user.
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

$label_name = elgg_echo('usersettings:statistics:label:name');
$label_email = elgg_echo('usersettings:statistics:label:email');
$label_member_since = elgg_echo('usersettings:statistics:label:membersince');
$label_last_login = elgg_echo('usersettings:statistics:label:lastlogin');

$time_created = date("r", $user->time_created);
$last_login = date("r", $user->last_login);

$title = elgg_echo('usersettings:statistics:yourdetails');

$content = <<<__HTML
<table class="elgg-table-alt">
	<tr class="odd">
		<td class="column-one">$label_name</td>
		<td>$user->name</td>
	</tr>
	<tr class="even">
		<td class="column-one">$label_email</td>
		<td>$user->email</td>
	</tr>
	<tr class="odd">
		<td class="column-one">$label_member_since</td>
		<td>$time_created</td>
	</tr>
	<tr class="even">
		<td class="column-one">$label_last_login</td>
		<td>$last_login</td>
	</tr>
</table>
__HTML;

echo elgg_view_module('info', $title, $content);
