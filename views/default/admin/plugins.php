<?php

	/**
	 * Elgg administration plugin main screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Description of what's going on
		echo "<p>" . autop(elgg_echo("admin:plugins:description")) . "</p>";

		$limit = get_input('limit', 10);
		$offset = get_input('offset', 0);
		
		
	// Get the installed plugins
		$installed_plugins = $vars['installed_plugins'];
		$count = count($installed_plugins);
		
		$plugin_list = get_plugin_list();
		$max = 0;
		foreach($plugin_list as $key => $foo)
			if ($key > $max) $max = $key;
		
	// Display list of plugins
		$n = 0;
		foreach ($installed_plugins as $plugin => $data)
		{
			//if (($n>=$offset) && ($n < $offset+$limit))
				echo elgg_view("admin/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data, 'maxorder' => $max, 'order' => array_search($plugin, $plugin_list)));
			
			$n++;
		}
		
	// Diplay nav
	/*
		if ($count) 
		{
			 echo elgg_view('navigation/pagination',array(
												'baseurl' => $_SERVER['REQUEST_URI'],
												'offset' => $offset,
												'count' => $count,
														));
		}
	*/
?>