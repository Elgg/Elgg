<?php

    /**
	 * Elgg Friends
	 * Friend widget options
	 * 
	 * @package ElggFriends
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
	

    //the page owner
	$owner = get_user($vars['entity']->owner_guid);

    //the number of files to display
	$num = (int) $vars['entity']->num_display;
	if (!$num)
		$num = 8;
		
	//get the correct size
	$size = (int) $vars['entity']->icon_size;
	if (!$size || $size == 1){
		$size_value = "small";
	}else{
    	$size_value = "tiny";
	}
		
    // Get the users friends
	$friends = $owner->getFriends("", $num, $offset = 0);
		
	// If there are any $friend to view, view them
	if (is_array($friends) && sizeof($friends) > 0) {

		echo "<div id=\"widget_friends_list\">";

		foreach($friends as $friend) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friend->guid), 'size' => $size_value));
			echo "</div>";
		}

		echo "</div>";
			
    }
	
?>