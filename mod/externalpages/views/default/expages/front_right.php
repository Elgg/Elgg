<?php

	/**
	 * Elgg Frontpage right
	 * 
	 * @package ElggExpages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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