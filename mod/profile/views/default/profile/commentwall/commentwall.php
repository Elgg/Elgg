<?php
/**
 * Elgg Commentwall display page
 */
//start the div which will wrap all the message board contents
echo "<div id='comment_wall_display'>";	 

// If there is any content to view, view it
if (is_array($vars['annotation']) && sizeof($vars['annotation']) > 0) {		
	//loop through all annotations and display
	foreach($vars['annotation'] as $content) {
		echo elgg_view("profile/commentwall/commentwall_content", array('annotation' => $content));
	}			
} else {
	echo "<p class='margin_top'>" . elgg_echo("profile:commentwall:none") . "</p>";
}
//close the wrapper div
echo "</div>";