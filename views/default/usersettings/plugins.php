<?php
	/**
	 * Elgg plugin specific user settings.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey 
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Description of what's going on
		echo "<p>" . nl2br(elgg_echo("usersettings:plugins:description")) . "</p>";

		$limit = get_input('limit', 10);
		$offset = get_input('offset', 0);
		
		
	// Get the installed plugins
		$installed_plugins = $vars['installed_plugins'];
		$count = count($installed_plugins);
		
	// Display list of plugins
		$n = 0;
		foreach ($installed_plugins as $plugin => $data)
		{
			if (($n>=$offset) && ($n < $offset+$limit))
				echo elgg_view("usersettings/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data));
			
			$n++;
		}
		
	// Diplay nav
		if ($count) 
		{
			 echo elgg_view('navigation/pagination',array(
												'baseurl' => $_SERVER['REQUEST_URI'],
												'offset' => $offset,
												'count' => $count,
														));
		}
?>