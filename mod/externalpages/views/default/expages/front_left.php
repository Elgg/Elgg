<?php

	/**
	 * Elgg Frontpage left
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
		
		if($contents){
			foreach($contents as $c){
				echo $c->title; // title is the left hand content
			}
		}else{
			echo "<p>" . elgg_echo("expages:addcontent") . "</p>";
		}
		
?>

