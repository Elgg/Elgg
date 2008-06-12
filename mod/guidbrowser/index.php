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

	require_once("../../engine/start.php");
	
	$eguid = get_input('eguid');
	$limit = get_input('limit', 10);
	$offset = get_input('offset');
	$type = get_input('type');
	$subtype = get_input('subtype');
	
	$action = get_input('subtype');
	$key = get_input('key');
	$value = get_input('value');
	
	$relationship = get_input('relationship');
	$guid2 = get_input('guid2');
	
	
	switch ($callaction)
	{
		case 'metadata' :
			if (!create_metadata($eguid, $key, $value))
				echo "Could not create metadata with $eguid:$key:$value";
		break;
			
		case 'annotations' :
			if (!create_annotation($eguid, $key, $value))
				echo "Could not create metadata with $eguid:$key:$value";
		break;

		case 'relationship' :
			if (!add_entity_relationship($eguid, $relationship, $guid2))
				echo "Could not create relationship between $eguid:$relationship:$guid2";
		break;
	}
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		
	// Display 
		$body = elgg_view_layout("one_column", guidbrowser_display($offset, $limit, $type, $subtype));
		
	// Display page
		page_draw(elgg_echo("guidbrowser"), $body);
?>