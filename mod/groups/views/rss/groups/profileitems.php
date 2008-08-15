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
	 
	 //right column
	 echo elgg_view("groups/forum_latest",array('entity' => $vars['entity']));
	 echo elgg_view("groups/right_column",array('entity' => $vars['entity']));
	 
	 //left column
	 echo elgg_view("groups/left_column",array('entity' => $vars['entity']));	 
	 
?>