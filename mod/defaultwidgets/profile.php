<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * 
 **/

// load Elgg engine
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/engine/start.php");

// make sure only admins can view this
admin_gatekeeper ();
set_context ( 'admin' );

// set admin user for user block
set_page_owner ( get_loggedin_userid() );

// vars required for action gatekeeper
$ts = time ();
$token = generate_action_token ( $ts );
$context = 'profile';

// create the view
$content = elgg_view ( "defaultwidgets/editor", array ('token' => $token, 'ts' => $ts, 'context' => $context ) );

// Display main admin menu
page_draw ( 'Default profile widgets for new users', $content );
