<?php

	/**
	 * Elgg widgets library.
	 * Contains code for handling widgets.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */


	/**
	 * Registers a particular action with a widget handler
	 *
	 * @param string $handler_name
	 * @param unknown_type $action
	 */
		function add_widget_save_action($handler_name, $action) {
			
			global $CONFIG;
			if (!isset($CONFIG->widgets->saveactions))
				$CONFIG->widgets->saveactions = array();
				
			if (!empty($handler_name)) {
				$CONFIG->widgets->saveactions[$handler_name] = $action;
			}
			
		}
		
	/**
	 * When given a widget entity and a new requested location, saves the new location
	 * and also provides a sensible ordering for all widgets in that column
	 *
	 * @param ElggObject $widget The widget entity
	 * @param int $order The order within the column
	 * @param int $column The column (1 or 2)
	 * @return true|false Depending on success
	 */
		function save_widget_location(ElggObject $widget, $order, $column) {
			
			if ($widget instanceof ElggObject) {
				if ($widget->subtype == "widget") {
					
					// If you can't move the widget, don't save a new location
					if (!$widget->draggable)
						return false;
					
					// Sanitise the column value
					if ($column != 1 || $column != 2)
						$column = 1;
						
					$widget->column = (int) $column;
					
					$ordertmp = array();
					
					if ($entities = get_entities_from_metadata_multi(array(																		
							'context' => $widget->context,
							'column' => $column,
							),'object','widget')) {
						foreach($entities as $entity) {
							$entityorder = $entity->order;
							if ($entityorder < $order) {
								$ordertmp[$entityorder] = $entity;								 
							}
							if ($entityorder >= $order) {
								$ordertmp[$entityorder + 10000] = $entity;
							}
						}	
					}
					
					$ordertmp[$order] = $widget;
					ksort($ordertmp);
					
					$orderticker = 10;
					foreach($ordertmp as $orderval => $entity) {
						$entity->order = $orderticker;
						$orderticker += 10;
					}
					
					return true;
					
				}
				
			}
			
			return false;
			
		}
		
	/**
	 * Get widgets for a particular context and column, in order of display
	 *
	 * @param int $user_guid The owner user GUID
	 * @param string $context The context (profile, dashboard etc)
	 * @param int $column The column (1 or 2)
	 * @return array|false An array of widget ElggObjects, or false
	 */
		function get_widgets($user_guid, $context, $column) {
			
			if ($widgets = get_user_objects_by_metadata($user_guid, "widget", array(
												'column' => $column,
												'location' => $context, 
																	), 10000)) {

																		
				$widgetorder = array();
				foreach($widgets as $widget) {
					$widgetorder[$widget->order] = $widget; 
				}
				return $widgetorder;
																		
			}
			
			return false;
			
		}

	/**
	 * Displays a particular widget
	 *
	 * @param ElggObject $widget The widget to display
	 * @param boolean $edit Should we display edit mode?
	 * @return string The HTML for the widget, including JavaScript wrapper
	 */
		function display_widget(ElggObject $widget, $edit = false) {
			
			if ($edit) {
				$body = elgg_view("widgets/" . $widget->handler . "/edit", array('entity' => $widget));
			} else {
				$body = elgg_view("widgets/" . $widget->handler . "/view", array('entity' => $widget));
			} 
			
			return elgg_view("widgets/wrapper",array('body' => $body, 'widget' => $widget, 'edit' => $edit));
			
		}
		
	/**
	 * Add a new widget
	 *
	 * @param int $user_guid User GUID to associate this widget with
	 * @param string $handler The handler for this widget
	 * @param string $context The page context for this widget
	 * @param int $order The order to display this widget in
	 * @param int $column The column to display this widget in (1 or 2)
	 * @return true|false Depending on success
	 */
		function add_widget($user_guid, $handler, $context, $order = 0, $column = 1) {
			
			if (empty($user_guid) || empty($context) || empty($handler))
				return false;
			
			if ($user = get_user($user_guid)) {
				
				$widget = new ElggObject;
				$widget->handler = $handler;
				$widget->context = $context;
				if (!$widget->save())
					return false;
				return save_widget_location($widget, $order, $column);
				
			}
			
			return false;
			
		}


?>