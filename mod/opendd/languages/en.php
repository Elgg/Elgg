<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'opendd' => "OpenDD",
			'opendd:your' => "Your activity",
			'opendd:feeds' => "Your subscriptions",
			'opendd:manage' => "Manage subscriptions",
			'opendd:edit' => "Edit subscription",

			'opendd:feedurl' => "Feed URL",
	
			'opendd:notobject' => "Entity is not an object, this should not have happened.",
			'opendd:feednotok' => "Unable to edit your OpenDD feeds.",
			'opendd:feedok' => "Successfully subscribed to feed.",
	
			'opendd:deleted' => "Feed subscription deleted.",
			'opendd:notdeleted' => "Feed subscription not deleted.",
	
			'opendd:noopenddfound' => "No OpenDD elements found in stream.",
	
			'opendd:metadata:uuid' => "UUID of metadata",
			'opendd:metadata:entityuuid' => "Referring to UUID",
			'opendd:metadata:owneruuid' => "Owner",
			'opendd:metadata:key' => "Key",
			'opendd:metadata:value' => "Value",
	
			'opendd:entity:uuid' => "Universal Identifier",
			'opendd:entity:class' => "Class",
			'opendd:entity:subclass' => "Subclass",
	
			'opendd:published' => "Published",
	
			'opendd:nodata' => "There was a problem getting the feed, response: %s",
	
	);
					
	add_translation("en",$english);
?>