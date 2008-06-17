<?php
/*
 * Created on Sep 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
function activity_pagesetup(){
	
}

function activity_init(){
	global $CFG,$function;
	
    $function['activity:recent'][] = $CFG->dirroot . "mod/activity/lib/recent.php";
}
?>
