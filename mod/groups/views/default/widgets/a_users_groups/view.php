<?php

    /** 
      *  Group profile widget - this displays a users groups on their profile
      **/
      
    //the number of groups to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 4;
		
    //the page owner
	$owner = $vars['entity']->owner_guid;
      
    //$groups = get_users_membership($owner);
    $groups = list_entities("group", "", $owner, $number, true, false);
	
    echo $groups;
      
?>