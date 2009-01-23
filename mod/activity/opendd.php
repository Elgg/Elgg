<?php
	/**
	 * Elgg activity plugin opendd export of a given statement.
	 * 
	 * @package ElggActivity
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	
	$id = (int)get_input('statement_id');
	$type = sanitise_string(get_input('statement_type'));
	$metaname = sanitise_string(get_input('metaname'));
	
	$statement = construct_riverstatement_from_log(
				get_log_entry($id)
			);
	
	global $CONFIG;
			
	switch ($type)
	{
		case 'statement' : $result = activity_export_statement($statement, $id);
						   foreach ($result as $r) $body .=  "$r"; 
		break;
		case 'metadata' :
			
			$md = activity_export_field_from_statement($statement, $id, $metaname);
			$body .= "$md";
		break;
	}
	
	page_draw('',$body);
?>