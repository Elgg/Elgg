<?php
	/**
	 * Elgg log browser.
	 * 
	 * @package ElggLogBrowser
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$entry = $vars['entity']->entry;
	
	
	$by = get_entity($entry->performed_by_guid);
	$object = get_object_from_log_entry($entry->id);
	
	if (is_callable(array($object, 'getURL')))
		$obj_url = $object->getURL();
	
	$icon = elgg_view(
			"logbrowser/icon", array(
			'entity' => $vars['entity'],
			'size' => 'small',
		  )
		);

	$info .= "<p><b>"; 
	if ($obj_url) $info .= "<a href=\"$obj_url\">";
	$info .= "{$entry->object_class}";
	if ($obj_url) $info .= "</a>";
	$info .= " " . elgg_echo($entry->event) . "</b></p>";
	
	$info .= "<div>" . elgg_echo('by') . " <a href=\"".$by->getURL()."\">{$by->name}</a> ".date('r', $entry->time_created )."</div>";
	
	echo elgg_view_listing($icon, $info);
?>