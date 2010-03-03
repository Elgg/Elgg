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

// load Elgg engine
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/engine/start.php");

// make sure only admins can view this
admin_gatekeeper ();
set_context ( 'admin' );

// Set admin user for user block
set_page_owner ( $_SESSION ['guid'] );

// vars required for action gatekeeper
$ts = time ();
$token = generate_action_token ( $ts );
$context = 'dashboard';

// create the view
$content = elgg_view ( "defaultwidgets/editor", array ('token' => $token, 'ts' => $ts, 'context' => $context ) );

// Display main admin menu
page_draw ( 'Default dashboard widgets for new users', $content );
