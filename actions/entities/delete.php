<?php
	/**
	 * Default entity delete action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	gatekeeper();
	
	$guid = get_input('guid');
	
	$entity = get_entity($guid);
	
	if (($entity) && ($entity->canEdit()))
	{
		if ($entity->delete())
			system_message(sprintf(elgg_echo('entity:delete:success'), $guid));
		else
			register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
	}
	else
		register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
		
	forward($_SERVER['HTTP_REFERER']);
?>