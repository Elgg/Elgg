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
			$site->save();
			
			datalist_set('path',get_input('path'));
			datalist_set('dataroot',get_input('dataroot'));
			
			datalist_set('default_site',$site->getGUID());
			
			system_message(elgg_echo("installation:configuration:success"));
			
			header("Location: ../../register.php");
			exit;
			
		}
		
	}

?>