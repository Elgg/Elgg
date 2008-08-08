<?php
	/**
	 * Elgg river plugin.
	 * This function 
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the river system and add a widget.
	 */
	function river_init() 
	{
		add_widget_type('river_widget',elgg_echo('river:widget:title'), elgg_echo('river:widget:description'));
		add_widget_type('river_widget_friends',elgg_echo('river:widget:title:friends'), elgg_echo('river:widget:description:friends'));
	}

	register_elgg_event_handler('init','system','river_init');
?>