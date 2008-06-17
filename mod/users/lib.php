<?php

/*
 * Created on Nov 21, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function users_pagesetup() {

}

function users_init() {
	global $CFG, $function;
	// Actions to perform on initialisation
	$function['init'][] = dirname(__FILE__) . "/lib/function_session_start.php";
	$function['init'][] = dirname(__FILE__) . "/lib/function_session_actions.php";
	$function['init'][] = dirname(__FILE__) . "/lib/function_default_access_levels.php";
	$function['init'][] = dirname(__FILE__) . "/lib/function_define_ownership.php";

	// User details initialisation
	$function['userdetails:init'][] = dirname(__FILE__) . "/lib/userdetails_actions.php";

	// Actions to perform when we log on
	$function['users:log_on'][] = dirname(__FILE__) . "/lib/function_log_on.php";

	// Actions to perform when we log off
	$function['users:log_off'][] = dirname(__FILE__) . "/lib/function_log_off.php";

	// Userinfo box
	$function['users:infobox'][] = dirname(__FILE__) . "/lib/user_info.php";

	// User count underneath the logon pane
	// $function['display:log_on_pane'][] = dirname(__FILE__) . "/lib/current_user_info.php";
	$function['display:log_on_pane'][] = dirname(__FILE__) . "/lib/function_number_of_users.php";
	//$function['display:sidebar'][] = dirname(__FILE__) . "/lib/current_user_info.php";
	//$function['display:sidebar'][] = dirname(__FILE__) . "/lib/function_number_of_users.php";

	// Access level select
	$function['display:access_level_select'][] = dirname(__FILE__) . "/lib/function_access_level_select.php";

	// Check access levels
	$function['users:access_level_check'][] = dirname(__FILE__) . "/lib/function_access_level_check.php";

	// Obtain SQL "where" string for access levels
	$function['users:access_level_sql_where'][] = dirname(__FILE__) . "/lib/function_access_level_sql_where.php";

	// User details edit screen
	$function['userdetails:edit'][] = dirname(__FILE__) . "/lib/userdetails_edit.php";

	// Permissions checker
	$function['permissions:check'][] = dirname(__FILE__) . "/lib/permissions_check.php";

	//@todo Remove deprecated functions
	// Functions to turn a username into a user ID and vice versa
	$function['users:name_to_id'][] = dirname(__FILE__) . "/lib/function_name_to_id.php"; // DEPRECATED - use user_info_username("ident", $username)
	$function['users:id_to_name'][] = dirname(__FILE__) . "/lib/function_id_to_name.php"; // DEPRECATED - use user_info("username", $user_id)
	// Get user type
	$function['users:type:get'][] = dirname(__FILE__) . "/lib/get_type.php"; // DEPRECATED - use user_type($user_id) or user_info("user_type", $ident)

	// Display a user's name, given a user ID
	$function['users:display:name'][] = dirname(__FILE__) . "/lib/function_display_name.php"; // DEPRECATED - use user_name($id)
	// Flag functions:
	// Check the value of a flag
	$function['users:flags:get'][] = dirname(__FILE__) . "/lib/flag_get.php"; // DEPRECATED - use user_flag_get($flag_name, $user_id)
	// Set the value of a flag
	$function['users:flags:set'][] = dirname(__FILE__) . "/lib/flag_set.php"; // DEPRECATED - use user_flag_set($flag_name, $value, $user_id)
	// Remove a flag
	$function['users:flags:unset'][] = dirname(__FILE__) . "/lib/flag_unset.php"; // DEPRECATED - use user_flag_unset($flag_name, $user_id)

    register_user_type('person');
}
?>
