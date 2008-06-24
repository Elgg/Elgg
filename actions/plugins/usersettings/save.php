<?php
	/**
	 * Elgg plugin user settings save action.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$params = get_input('params');
	$plugin = get_input('plugin');

	$result = false;
	
	foreach ($params as $k => $v)
	{
		// Save
		$result = set_plugin_usersetting($k, $v, $_SESSION['user']->guid, $plugin);
		
		// Error?
		if (!$result)
		{
			system_message(sprintf(elgg_echo('plugins:usersettings:save:fail'), $plugin));
			
			forward($_SERVER['HTTP_REFERER']);
			
			exit;
		}
	}

	system_message(sprintf(elgg_echo('plugins:usersettings:save:ok'), $plugin));
	forward($_SERVER['HTTP_REFERER']);
?>