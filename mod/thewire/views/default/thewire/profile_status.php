<?php

	/**
	 * Latest wire post on profile activity page
	 */
	 
	$owner = $vars['entity']->guid;
	$url_to_wire = $vars['url'] . "pg/thewire/" . $vars['entity']->username;
	
	//grab the users latest from the wire
	$latest_wire = elgg_get_entities(array('types' => 'object', 'subtypes' => 'thewire', 'owner_guid' => $owner, 'limit' => 1)); 

	if($latest_wire){
		foreach($latest_wire as $lw){
			$content = $lw->description;
			$time = "<p class='entity_subtext'> (" . elgg_view_friendly_time($lw->time_created) . ")</p>";
		}
	}
	
	if($latest_wire){
		echo "<div class='wire_post'><div class='wire_post_contents clearfloat radius8'>";
		echo $content;
		if($owner == $_SESSION['user']->guid)
			echo " <a class='action_button update small' href=\"{$url_to_wire}\">update</a>";
		echo $time;
		echo "</div></div>";
	}
?>