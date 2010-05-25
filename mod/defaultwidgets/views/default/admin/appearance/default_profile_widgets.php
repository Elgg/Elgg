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

// set admin user for user block
set_page_owner($_SESSION['guid']);

// create the view
$time = time();
echo elgg_view('defaultwidgets/editor', array (
	'token' => generate_action_token($time),
	'ts' => $time,
	'context' => 'profile',
));
