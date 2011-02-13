<?php

	/**
	 * Elgg Frontpage left
	 * 
	 * @package ElggExpages
	 * 
	 */

	 
	 //get frontpage right code
		$contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'front', 'limit' => 1));
		
		if($contents){
			foreach($contents as $c){
				echo $c->title; // title is the left hand content
			}
		}else{
			echo "<p>" . elgg_echo("expages:addcontent") . "</p>";
		}
		
?>

