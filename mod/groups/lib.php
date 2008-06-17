<?php
/*
 * Created on Sep 23, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function groups_pagesetup() {
	global $PAGE,$CFG,$profile_id;
    $page_owner = $profile_id;
	
	if (defined("context") && context == "network") {
		if (isloggedin() && $page_owner == $_SESSION['userid']) {
			$PAGE->menu_sub[] = array (
				'name' => 'friend:accesscontrols',
				'html' => a_href("{$CFG->wwwroot}mod/groups/",__gettext("Access controls")));
		}
	}
}

function groups_init() {
	global $CFG, $function;

	// Functions to perform upon initialisation
	$function['groups:init'][] = $CFG->dirroot . "mod/groups/lib/groups_actions.php";

	// Add user-owned groups to access levels
	$function['init'][] = $CFG->dirroot . "mod/groups/lib/groups_access_levels.php";

	// Function to retrieve groups
	$function['groups:get'][] = $CFG->dirroot . "mod/groups/lib/get_groups.php";
	$function['groups:get:external'][] = $CFG->dirroot . "mod/groups/lib/get_groups_external.php";
	$function['groups:getmembership'][] = $CFG->dirroot . "mod/groups/lib/get_groups_membership.php";

	// Group view / edit screen
	// $function['groups:editpage'][] = $CFG->dirroot . "mod/groups/lib/groups_display_membership.php";
	$function['groups:editpage'][] = $CFG->dirroot . "mod/groups/lib/groups_explanation.php";
	$function['groups:editpage'][] = $CFG->dirroot . "mod/groups/lib/groups_create.php";
	$function['groups:editpage'][] = $CFG->dirroot . "mod/groups/lib/groups_edit_existing.php";

	// Individual group editing function
	$function['groups:edit:display'][] = $CFG->dirroot . "mod/groups/lib/groups_edit_display.php";

	// Check access levels
	$function['users:access_level_check'][] = $CFG->dirroot . "mod/groups/lib/group_access_level_check.php";

	// Obtain SQL "where" string for access levels
	$function['users:access_level_sql_where'][] = $CFG->dirroot . "mod/groups/lib/function_access_level_sql_where.php";

}
?>
