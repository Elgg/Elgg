<?php

	/**
	 * New wire post view for the activity stream
	 */
	 
	$owner = $vars['entity']->guid;

	//grab the users latest from the wire
	$latest_wire = get_entities("object", "thewire", $owner, "", 1, 0, false, 0, null); 

	if($latest_wire){
		foreach($latest_wire as $lw){
			$content = $lw->description;
			$time = "<span>" . friendly_time($lw->time_created) . "</span>";
		}
	}
	
	if($latest_wire){
		echo "<div class=\"profile_status\">";
		echo $content . " " . $time;
		echo "</div>";
	}
?>