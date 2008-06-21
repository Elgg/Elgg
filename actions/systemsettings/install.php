<?php

	/**
	 * Elgg install site action
	 * 
	 * Creates a nwe site and sets it as the default
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	if (get_input('settings') == 'go') {
		
		if (!datalist_get('default_site')) {
			
			$site = new ElggSite();
			$site->name = get_input('sitename');
			$site->url = get_input('wwwroot');
			$site->access_id = 2; // The site is public
			$guid = $site->save();
			
			if (!$guid)
				throw new InstallationException(sprintf(elgg_echo('InstallationException:CantCreateSite'), get_input('sitename'), get_input('wwwroot')));
			
			datalist_set('installed',time());
			
			datalist_set('path',get_input('path'));
			datalist_set('dataroot',get_input('dataroot'));
			
			datalist_set('default_site',$site->getGUID());
			
			set_config('view', get_input('view'), $site->getGUID());
			set_config('language', get_input('language'), $site->getGUID());
			
			$debug = get_input('debug');
			if ($debug)
				set_config('debug', 1, $site->getGUID());
			else
				unset_config('debug', $site->getGUID());
			
			// activate profile by default
			enable_plugin('profile', $site->getGUID());
				
			system_message(elgg_echo("installation:configuration:success"));
			
			header("Location: ../../register.php");
			exit;
			
		}
		
	}

?>