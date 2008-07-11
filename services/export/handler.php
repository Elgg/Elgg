<?php
	/**
	 * Open Document Definition Handler.
	 * This file acts as the endpoint for ODD UUID url requests, exporting the requested data as an
	 * ODD XML file. 
	 * 
	 * @package Elgg
	 * @subpackage ODD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	require_once("../../engine/start.php");

	// Get input values, these will be mapped via modrewrite
	$guid = get_input("guid"); // guid of the entity

	// For attributes eg http://example.com/odd/73/attr/owner_uuid/ or http://example.com/odd/73/metadata/86/
	$type = get_input("type"); // attr, metadata, annotation, rekationship
	$id_or_name = get_input("idname"); // Either a number or the key name (if attribute)
	
	
	
	// Only export the GUID
	if (
		($guid!="") &&
		($type=="") &&
		($id_or_name=="")
	)
	{
		page_draw("GUID:$guid", elgg_view("export/entity", array("entity" => get_entity($guid), "uuid" => guid_to_uuid($guid))));
	}
	
	// Export an individual attribute
	else if (
		($guid!="") &&
		($type!="") &&
		($id_or_name!="")
	)
	{
		// Get a uuid
		$entity = get_entity($guid);
		$uuid = guid_to_uuid($entity->getGUID()) . "$type/$id_or_name/";
		
		switch ($type)
		{
			case 'attr' : // TODO: Do this better? - This is a bit of a hack...
				$v = $entity->get($id_or_name);
				if (!$v) throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:IdNotExistForGUID'), $id_or_name, $guid));
				
				$m = new ElggMetadata();
				
				$m->value = $v;
				$m->name = $id_or_name;
				$m->entity_guid = $guid;
				$m->time_created = $entity->time_created;
				$m->time_updated = $entity->time_updated;
				$m->owner_guid = $entity->owner_guid;
				$m->id = $id_or_name;
				$m->type = "attr";
			break;
			case 'metadata' : 
				$m = get_metadata($id_or_name);
			break;
			case 'annotation' : 
				$m = get_annotation($id_or_name);
			break;	
			case 'relationship' :
				$r = get_relationship($id_or_name);
			break;
				
			default :
				throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:CanNotExportType'), $type));
		}
		
		// Render metadata or relationship
		if ((!$m) && (!$r))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:NoDataFound'));
			
		// Exporting metadata?
		if ($m)
		{
			if ($m->entity_guid!=$entity->guid)
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:DoesNotBelong'));
			
			page_draw("$type:$id_or_name", elgg_view("export/metadata", array("metadata" => $m, "uuid" => $uuid)));
		}
		
		// Exporting relationship
		if ($r)
		{
			if (
				($r->guid_one!=$entity->guid) &&
				($r->guid_two!=$entity->guid)
			)
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:DoesNotBelongOrRefer'));
			
			page_draw("$type:$id_or_name", elgg_view("export/relationship", array("relationship" => $r, "uuid" => $uuid)));
		}
	}
	
	// Something went wrong
	else
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:MissingParameter'));
	
?>