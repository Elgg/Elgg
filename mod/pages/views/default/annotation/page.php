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

	$annotation = $vars['annotation'];
	$entity = get_entity($annotation->entity_guid);
	
	$icon = elgg_view(
		"annotation/icon", array(
		'annotation' => $vars['annotation'],
		'size' => 'small',
	  )
	);
	
	$owner_guid = $annotation->owner_guid;
	$owner = get_entity($owner_guid);
			
	$rev = sprintf(elgg_echo('pages:revision'), 
		friendly_time($annotation->time_created),
		
		"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
	);
	
	$link = $entity->getURL() . "?rev=" . $annotation->id;
	
	$info = <<< END
	
<div><a href="$link">{$entity->title}</a></div>
<div>$rev</div>
END;

	echo elgg_view_listing($icon, $info);
?>