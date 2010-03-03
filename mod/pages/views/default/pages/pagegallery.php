<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$icon = elgg_view(
			"graphics/icon", array(
			'entity' => $vars['entity'],
			'size' => 'medium',
		  )
		);

	$info = "<div><p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->title . "</a></b></p></div>";

	
	$latest = $vars['entity']->getAnnotations('page', 1, 0, 'desc');
	if ($latest) {
		$latest = $latest[0];
	
		$time_updated = $latest->time_created;
		$owner_guid = $latest->owner_guid;
		$owner = get_entity($owner_guid);
		
			
		$info .= "<br /><div>".
			strip_tags(substr($latest->value, 0, 100))
		 . "</div>";
	}

	echo elgg_view_listing($icon, $info);
?>