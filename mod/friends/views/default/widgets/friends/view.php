<?php

/**
 * Elgg Friends
 * Friend widget display view
 *
 * @package ElggFriends
 * @subpackage Core
 */


// owner of the profile page
$owner = get_user($vars['entity']->owner_guid);

// the number of friends to display
$num = (int) $vars['entity']->num_display;

// get the correct size
$size = $vars['entity']->icon_size;

// Get the user's friends
$friends = $owner->getFriends("", $num);

// If there are any friends to view, view them
if (is_array($friends) && sizeof($friends) > 0) {

	echo "<div id=\"widget_friends_list\">";

	foreach($friends as $friend) {
		echo "<div class=\"widget_friends_singlefriend\" >";
		echo elgg_view("profile/icon",array('entity' => get_user($friend->guid), 'size' => $size));
		echo "</div>";
	}

	echo "</div>";
}
