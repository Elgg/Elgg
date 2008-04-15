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

	require_once("../engine/start.php");

	// Get input values, these will be mapped via modrewrite
	$guid = get_input("guid"); // guid of the entity

	// For attributes eg http://example.com/odd/73/attr/owner_uuid/ or http://example.com/odd/73/metadata/86/
	$type = get_input("type"); // attr, metadata, annotation
	$id_or_name = get_input("idname"); // Either a number or the key name (if attribute)
	
	$owner = page_owner();	// Obvious
$_SESSION['id'] = 2;

	if (
		($guid!="") &&
		($type=="") &&
		($id_or_name=="")
	)
	{
		// Only export the GUID, easy.
		$export = export($guid);
		
		header("Content-Type: text/xml");
		echo $export;
	}
	else if (
		($guid!="") &&
		($type!="") &&
		($id_or_name!="")
	)
	{
		// Outputting an individual attribute
		
	
		$odd = "";
		$entity = get_entity($guid);
		$uuid = guid_to_uuid($entity->getGUID());
		
		if (!$entity) throw new InvalidParameterException("Could not find an entity matching query.");
				
		switch ($type)
		{
			case 'attr' : // TODO: Do this better
				$odd = new ODDMetaData($uuid . "attr/$id_or_name/", $uuid, $id_or_name, $entity->get($id_or_name));
			break;
			
			case 'metadata' :
				$m = get_metadata($id_or_name);
				if (!$m)
					throw new InvalidParameterException("Could not find specified item of metadata");
					
				if ($m->entity_guid!=$entity->guid)
					throw new InvalidParameterException("Does not belong to entity.");
					
				$odd = new ODDMetaData($uuid . "metadata/$id_or_name/", $uuid, $id_or_name, $m->value);
			break;
			
			case 'annotation' : 
				$m = get_annotation($id_or_name);
				if (!$m)
					throw new InvalidParameterException("Could not find specified annotation");
					
				if ($m->entity_guid!=$entity->guid)
					throw new InvalidParameterException("Does not belong to entity.");
					
				$odd = new ODDMetaData($uuid . "annotation/$id_or_name/", $uuid, $id_or_name, $m->value);
			break;
			
			default :
				throw new InvalidParameterException("Sorry, I don't know how to export '$type'");
				
		}
		
		// echo it
		header("Content-Type: text/xml");
		echo "<odd>\n";
		echo new ODDHeader();
		echo "$odd";
		echo "</odd>\n";
	}
	else
		throw new InvalidParameterException("Missing parmeter, you need to provide a GUID ");
?>