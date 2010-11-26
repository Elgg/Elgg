<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * 
 * Code based on the work of:
 * @author Jade Dominguez, Chad Sowald
 * @copyright tastyseed,  2008
 * @copyright Chad Sowald, 2008
 * @link http://www.tastyseed.com
 * @link http://www.chadsowald.com
 * @author Diego Ramirez
 * @links http://www.somosmas.org
 * 
 */

global $CONFIG;

/**
 * Default widgets initialisation
 *
 * These parameters are required for the event API, but we won't use them:
 * 
 * @param unknown_type $event
 * @param unknown_type $object_type
 * @param unknown_type $object
 */
function defaultwidgets_init() {
	
	// Load system configuration
	register_page_handler ( 'defaultwidgets', 'defaultwidgets_page_handler' );
	
	// register create user event hook
	register_elgg_event_handler ( 'create', 'user', 'defaultwidgets_newusers' );
	
	// set the widget access to the default access on validation if this is not an admin-created user
	if (!isadminloggedin()) {
		register_elgg_event_handler('validate', 'user', 'defaultwidgets_reset_access');
	}
	
	// Override metadata permissions
	//register_plugin_hook ( 'permissions_check:metadata', 'object', 'defaultwidgets_can_edit_metadata' );
}

/**
 * Overrides default permissions for the default widgets context
 * 
 */
function defaultwidgets_can_edit($hook_name, $entity_type, $return_value, $parameters) {
	global $defaultwidget_access;
	
	if ($defaultwidget_access) {
		return true;
	}
	return $return_value;
}

/**
 * Override the canEditMetadata function to return true for messages
 *
 */
function defaultwidgets_can_edit_metadata($hook_name, $entity_type, $return_value, $parameters) {
	global $defaultwidget_access;
	
	if ($defaultwidget_access) {
		return true;
	}
	return $return_value;

}

/**
 * Override the canEdit function to return true for messages within a particular context.
 *
 */
function defaultwidgets_can_edit_container($hook_name, $entity_type, $return_value, $parameters) {
	global $defaultwidget_access;
	
	if ($defaultwidget_access) {
		return true;
	}
	return $return_value;
}

/**
 * Extends the create user event to add admin defined widgets to the dashboard/profile context
 */
function defaultwidgets_newusers($event, $object_type, $object) {
	
	// turn on permissions override
	global $defaultwidget_access, $CONFIG;
	$defaultwidget_access = true;
	
	// get the new user guid
	$guid = $object->guid;
	
	if (isadminloggedin()) {
		// this is an admin-created user
		// no permissions problems, so set proper access now
		// use system default access (not the admin's default access!, because that could be a personal access level)
		$widget_access = $CONFIG->default_access;
	} else {
		// this is a regular registration
		// set widget access to public for now and reset it properly during the validate event
		// to avoid Elgg permissions problems
		$widget_access = ACCESS_PUBLIC;
	}
	
	// check if it's set
	if (! empty ( $guid )) {
		
		// get the user entity
		if ($user = get_entity ( $guid )) {
			
			// can this user edit
			if ($user->canEdit ()) {
				
				// each of the contexts to add widgets for
				$contexts = array ('profile', 'dashboard' );
				
				// get the entities for the module
				$entities = elgg_get_entities (array('type' => 'object', 'subtype' => 'moddefaultwidgets', 'limit' => 9999));
				
				// check if the entity exists
				if (isset ( $entities [0] )) {
					
					// get the widgets for the context
					$entity = $entities [0];
					
					foreach ( $contexts as $context ) {
						$current_widgets = $entity->$context;
						list ( $left, $middle, $right ) = split ( '%%', $current_widgets );
						
						// split columns into seperate widgets
						$area1widgets = split ( '::', $left );
						$area2widgets = split ( '::', $middle );
						$area3widgets = split ( '::', $right );
						
						// clear out variables if no widgets are available
						if ($area1widgets [0] == "")
							$area1widgets = false;
						if ($area2widgets [0] == "")
							$area2widgets = false;
						if ($area3widgets [0] == "")
							$area3widgets = false;
							
						// generate left column widgets for a new user 
						if ($area1widgets) {
							foreach ( $area1widgets as $i => $widget ) {
								add_widget ( $guid, $widget, $context, ($i + 1), 1, $widget_access );
							}
						}
						
						// generate middle column widgets for a new user
						if ($area2widgets) {
							foreach ( $area2widgets as $i => $widget ) {
								add_widget ( $guid, $widget, $context, ($i + 1), 2, $widget_access );
							}
						}
						
						// generate right column widgets for a new user
						if ($area3widgets) {
							foreach ( $area3widgets as $i => $widget ) {
								add_widget ( $guid, $widget, $context, ($i + 1), 3, $widget_access );
							}
						}
					}
				}
			}
		}
	}
	
	// turn off permissions override
	$defaultwidget_access = false;
}

function defaultwidgets_reset_access($event, $object_type, $object) {
	
	global $defaultwidget_access;
	
	// turn on permissions override
	$defaultwidget_access = true;
	
	// the widgets are disabled, so turn on the ability to see disabled entities
	
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	$widgets = elgg_get_entities(array('type' => 'object', 'subtype' => 'widget', 'owner_guid' => $object->getGUID()));
	
	if ($widgets) {
		foreach($widgets as $widget) {
			$widget->access_id = get_default_access();
			$widget->save();
		}
	}
	
	access_show_hidden_entities($access_status);
	
	// turn off permissions override
	$defaultwidget_access = false;
	
	return true;
}

/**
 * Default widgets page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function defaultwidgets_page_handler($page) {
	global $CONFIG;
	
	if (isset ( $page [0] )) {
		
		switch ($page [0]) {
			case "profile" :
				include (dirname ( __FILE__ ) . "/profile.php");
				break;
			case "dashboard" :
				include (dirname ( __FILE__ ) . "/dashboard.php");
				break;
		}
	} else {
		register_error ( elgg_echo ( "defaultwidgets:admin:notfound" ) );
		forward ( $CONFIG->wwwroot );
	}
	return true;
}

/**
 * Page setup. Adds admin controls to the admin panel.
 *
 */
function defaultwidgets_pagesetup() {
	if (get_context () == 'admin' && isadminloggedin ()) {
		global $CONFIG;
		add_submenu_item ( elgg_echo ( 'defaultwidgets:menu:profile' ), $CONFIG->wwwroot . 'pg/defaultwidgets/profile' );
		add_submenu_item ( elgg_echo ( 'defaultwidgets:menu:dashboard' ), $CONFIG->wwwroot . 'pg/defaultwidgets/dashboard' );
	}
}

// Make sure the status initialisation function is called on initialisation
register_elgg_event_handler ( 'init', 'system', 'defaultwidgets_init' );
register_elgg_event_handler ( 'pagesetup', 'system', 'defaultwidgets_pagesetup' );

register_plugin_hook ( 'permissions_check', 'user', 'defaultwidgets_can_edit' );
register_plugin_hook ( 'permissions_check', 'object', 'defaultwidgets_can_edit' );
register_plugin_hook ( 'container_permissions_check', 'user', 'defaultwidgets_can_edit_container' );

register_action ( "defaultwidgets/update", false, $CONFIG->pluginspath . "defaultwidgets/actions/update.php" );
