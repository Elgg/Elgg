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
			$site->description = get_input('sitedescription');
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
				
			$usage = get_input('usage');
			if ($usage)
				unset_config('ping_home', $site->getGUID());
			else
				set_config('ping_home', 'disabled', $site->getGUID());
			
			// activate some plugins by default
			enable_plugin('profile', $site->getGUID());
			enable_plugin('river', $site->getGUID());
			enable_plugin('updateclient', $site->getGUID());
			enable_plugin('logbrowser', $site->getGUID());
			
			
			// Now ping home
			if ((!isset($usage)) || ($usage!='disabled'))
			{
				ping_home($site);
			}
				
			system_message(elgg_echo("installation:configuration:success"));
			
			header("Location: ../../account/register.php");
			exit;
			
		}
		
	}

?>