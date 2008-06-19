<?php
	/**
	 * Elgg river plugin.
	 * This function 
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the river system and add a widget.
	 */
	function river_init() 
	{
		register_plugin_manifest_basic("Marcus Povey", elgg_echo('river:manifest:description'), "1.0", "http://www.elgg.org", "(C) Curverider 2008");
		
		add_widget_type('river_widget',sprintf(elgg_echo('river:widget:title'), 'Your'), elgg_echo('river:widget:description'));
		add_widget_type('river_widget_friends',sprintf(elgg_echo('river:widget:title:friends'), 'Your'), elgg_echo('river:widget:description:friends'));
	}

	register_elgg_event_handler('init','system','river_init');
?>