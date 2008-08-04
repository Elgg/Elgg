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
	 
	//members
	 echo "<div class=\"group_narrow_column\" style=\"float:right;width:200px;\">";
	 echo elgg_view("groups/members",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //narrow column
	 echo "<div class=\"group_narrow_column\" style=\"float:right;width:200px;\">";
	 echo elgg_view("groups/narrow_column",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //wider column
	 echo "<div id=\"group_wide_column\">";
	 echo elgg_view("groups/wide_column",array('entity' => $vars['entity']));
	 echo "</div>";	 
	 
?>