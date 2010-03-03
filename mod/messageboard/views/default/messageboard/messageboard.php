<?php

    /**
	 * Elgg Message board display page
	 * 
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 

	 // If there is any content to view, view it
		if (is_array($vars['annotation']) && sizeof($vars['annotation']) > 0) {
    		
    		//start the div which will wrap all the message board contents
    		echo "<div id=\"messageboard_wrapper\">";
			
    		//loop through all annotations and display
			foreach($vars['annotation'] as $content) {
				
				echo elgg_view("messageboard/messageboard_content", array('annotation' => $content));
				
			}
			
			//close the wrapper div
			echo "</div>";
			
		} else {
    		
    		echo "<div class='contentWrapper'>" . elgg_echo("messageboard:none") . "</div>";
    		
		}
			
	 
?>