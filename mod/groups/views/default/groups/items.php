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
	 echo "<div id=\"group_members\" style=\"float:right;width:200px;\">";
	 echo elgg_view("groups/members",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //forums
	 echo "<div id=\"group_forums\">";
	 echo elgg_view("groups/forums",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //messageboard
	 echo "<div id=\"group_messageboard\">";
	 echo elgg_view("groups/messageboard",array('entity' => $vars['entity']));
	 echo "</div>";	 
	 
?>