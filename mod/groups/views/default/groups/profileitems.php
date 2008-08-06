<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	 
	 //narrow column
	 echo "<div class=\"right_column\" style=\"float:right;width:330px;border:1px solid #efefef;\">";
	 echo elgg_view("groups/right_column",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //wider column
	 echo "<div id=\"left_column\" style=\"width:330px;border:1px solid #efefef;\">";
	 echo elgg_view("groups/left_column",array('entity' => $vars['entity']));
	 echo "</div>";	 
	 
?>