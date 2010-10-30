<?php
/**
 * 
 */

$user = get_loggedin_user();

if (($user instanceof ElggUser) && ($user->guid > 0)) {
	$friends = elgg_echo('friends');
	echo "<a class='myfriends' href=\"".elgg_get_site_url()."pg/friends/{$user->username}\" title=\"$friends\">$friends</a>";
}
