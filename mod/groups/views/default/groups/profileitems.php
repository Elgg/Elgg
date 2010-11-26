<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 */
	 
	 //forum 
	 echo "<div class=\"clearfloat\"></div><div id=\"fullcolumn\">";
	 echo elgg_view("groups/forum_latest",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //right column
	 echo "<div id=\"right_column\">";
	 echo elgg_view("groups/right_column",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //left column
	 echo "<div id=\"left_column\">";
	 echo elgg_view("groups/left_column",array('entity' => $vars['entity']));
	 echo "</div>";	 
	 
?>