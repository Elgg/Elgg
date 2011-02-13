<?php

	/**
	 * Elgg Analytics view
	 * 
	 * @package ElggExpages
	 * 
	 */

	 
	 //get analytics content 
		$contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'analytics', 'limit' => 1));
		
		if($contents){
			foreach($contents as $c){
				echo $c->description;
			}
		}

?>

