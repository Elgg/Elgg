<?php

	/**
	 * Elgg Frontpage right
	 * 
	 * @package ElggExpages
	 * 
	 */
	 
	 //get frontpage right code
		$contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'front', 'limit' => 1));

		// nothing to show so we return TRUE to indicate the view was valid
		if ($contents == FALSE) {
			return TRUE;
		}

		$show = '';
		foreach($contents as $cont){
			$show = $cont->description;
		}

		if($show != ''){
			echo "<div id=\"index_welcome\">";

			if($contents){
				foreach($contents as $c){
					echo $c->description;
				}
			}else{
				echo elgg_echo("expages:addcontent");
			}
			echo "</div>";
		}

?>