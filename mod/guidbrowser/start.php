<?php
	/**
	 * Elgg GUID browser
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	function guidbrowser_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			// register_translations($CONFIG->pluginspath . "guidbrowser/languages/");
		add_menu("GUID Browser",$CONFIG->wwwroot . "mod/tasklist/",array(
				menu_item("The GUID browser",$CONFIG->wwwroot."mod/guidbrowser/"),
		));
	}
	
	function guidbrowser_displayentity($entity)
	{
		return elgg_view("guidbrowser/entity",
			array(
				'entity_guid' => $entity->guid,
				'type' => $entity->type,
				'subtype' => $entity->getSubtype(),
				'full' => elgg_view(
					"guidbrowser/entity_full",
					array(
						'entity' => $entity,
						'metadata' => get_metadata_for_entity($entity->guid),
						'annotations' => get_annotations($entity->guid),
						'relationships' => get_entity_relationships($entity->guid)
					)
				)
			) 
		);
	}
	
	function guidbrowser_display($offset = 0, $limit = 10, $type = "", $subtype = "")
	{
		$entities = get_entities($type, $subtype, page_owner(), "time_created desc", $limit, $offset);
		$display = "";

		foreach ($entities as $e)
			$display .= guidbrowser_displayentity($e);
		
		return elgg_view("guidbrowser/browser",
			array(
				'entities' => $display,
				'prevnext' => elgg_view("guidbrowser/prevnext", array("limit" => $limit, "offset" => $offset))
			)
		);
	}
	
	
	// Make sure test_init is called on initialisation
	register_elgg_event_handler('init','system','guidbrowser_init');
?>