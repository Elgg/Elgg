<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 //right column
	 if ($forae = elgg_get_entities(array('types' => 'object', 'container_guid' => $vars['entity']->guid))) {
	 //if ($forae = get_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 0, $vars['entity']->guid, 20, 0, "desc", false)) {
	 	foreach($forae as $forum)
	 		echo elgg_view_entity($forum);
	 }
	 
?>