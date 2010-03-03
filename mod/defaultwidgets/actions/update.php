<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU
 * @author Milan Magudia & Curverider
 * @copyright HedgeHogs.net & Curverider Ltd
 * 
 **/

// validate user is an admin
admin_gatekeeper ();

// get parameters
$context = get_input ( 'context' );
$leftbar = str_replace ( '::0', '', get_input ( 'debugField1' ) );
$middlebar = str_replace ( '::0', '', get_input ( 'debugField2' ) );
$rightbar = str_replace ( '::0', '', get_input ( 'debugField3' ) );

// make sure enough parameters are set
if ($context && isset ( $leftbar ) && isset ( $middlebar ) && isset ( $rightbar )) {
	
	// join widgets into a single string
	$widgets = $leftbar . '%%' . $middlebar . '%%' . $rightbar;
	
	// get the elgg object that contains our settings
	$entities = elgg_get_entities (array('type' => 'object', 'subtype' => 'moddefaultwidgets', 'limit' => 9999));
	
	// create new object unless one already exists
	if (! isset ( $entities [0] )) {
		$entity = new ElggObject ( );
		$entity->subtype = 'moddefaultwidgets';
		$entity->owner_guid = $_SESSION ['user']->getGUID ();
	} else {
		$entity = $entities [0];
	}
	
	// store the default widgets for each context
	$entity->$context = $widgets;
	
	// make sure this object is public.
	$entity->access_id = 2;
	
	// save the object or report error
	if ($entity->save ()) {
		system_message ( elgg_echo ( 'defaultwidgets:update:success' ) );
		$entity->state = "active";
		forward ( 'pg/admin' );
	} else {
		register_error ( elgg_echo ( 'defaultwidgets:update:failed' ) );
		forward ( 'pg/defaultwidgets/' . $context );
	}

} else {
	
	// report incorrect parameters error
	register_error ( elgg_echo ( 'defaultwidgets:update:noparams' ) );
	forward ( 'pg/defaultwidgets/' . $context );

}
