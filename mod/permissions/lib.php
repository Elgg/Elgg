<?php
/*
 * Created on Sep 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
function permissions_pagesetup() {

}
function permissions_init() {
	global $CFG,$function;
	
	$function['permissions:check'][] = $CFG->dirroot . "mod/permissions/lib/function_check.php";
}
?>
