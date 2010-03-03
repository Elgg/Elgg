<?php

	/**
	 * New wire post view for the activity stream
	 */
	 
	$owner = $vars['entity']->guid;
	$url_to_wire = $vars['url'] . "pg/thewire/" . $vars['entity']->username;
	
	//grab the users latest from the wire
	$latest_wire = elgg_get_entities(array('types' => 'object', 'subtypes' => 'thewire', 'owner_guid' => $owner, 'limit' => 1)); 

	if($latest_wire){
		foreach($latest_wire as $lw){
			$content = $lw->description;
			$time = "<span> (" . friendly_time($lw->time_created) . ")</span>";
		}
	}
	
	if($latest_wire){
		echo "<div class=\"profile_status\">";
		echo $content;
		if($owner == $_SESSION['user']->guid)
			echo " <a class=\"status_update\" href=\"{$url_to_wire}\">update</a>";
		echo $time;
		echo "</div>";
	}
?>