<?php

	/**
	 * Elgg Analytics view
	 * 
	 * @package ElggExpages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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

