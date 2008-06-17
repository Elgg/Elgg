<?php
/*
 * Created on Sep 23, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function display_pagesetup() {

}

function display_init() {
	global $CFG,$function;

	// Display functions

	// Initialise
	$function['init'][] = $CFG->dirroot . "mod/display/lib/function_init.php";

	// Log on pane
	$function['display:log_on_pane'][] = $CFG->dirroot . "mod/display/lib/function_log_on_pane.php";
	$function['display:sidebar'][] = $CFG->dirroot . "mod/display/lib/function_log_on_pane.php";

	// Form elements
	$function['display:input_field'][] = $CFG->dirroot . "mod/display/lib/function_input_field_display.php";
	$function['display:output_field'][] = $CFG->dirroot . "mod/display/lib/function_output_field_display.php";

	// TEMPLATING ---

	// Adds data to the various strings used in templating
	$function['display:addstring'][] = $CFG->dirroot . "mod/display/lib/function_display_addstring.php";

}
?>
