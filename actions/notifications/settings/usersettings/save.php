<?php
	/**
	 * Elgg notifications user preference save acion.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Method
	$method = get_input('method');
	gatekeeper();
	
	$result = false;
	foreach ($method as $k => $v)
	{
		$result = set_user_notification_setting($_SESSION['user']->guid, $k, ($v == 'yes') ? true : false);
		
		if (!$result)
		{
			register_error(elgg_echo('notifications:usersettings:save:fail'));
			//forward($_SERVER['HTTP_REFERER']);
			
			//exit;
		}
	}
	
	if ($result)
		system_message(elgg_echo('notifications:usersettings:save:ok'));
	else
		register_error(elgg_echo('notifications:usersettings:save:fail'));
	
	//forward($_SERVER['HTTP_REFERER']);
?>