<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 */
	 
	 //right column
	 if ($forae = elgg_get_entities(array('types' => 'object', 'container_guid' => $vars['entity']->guid))) {
	 	foreach($forae as $forum)
	 		echo elgg_view_entity($forum);
	 }
	 
?>