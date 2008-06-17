<?php

/*
 * lib.php
 *
 * Created on Apr 25, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function icons_pagesetup() {

}

function icons_init() {
  global $CFG;
  global $function;
  // Actions
  $function["icons:init"][]= $CFG->dirroot . "mod/icons/lib/function_actions.php";

  // Icon management
  $function["icons:edit"][]= $CFG->dirroot . "mod/icons/lib/function_edit_icons.php";
  $function["icons:add"][]= $CFG->dirroot . "mod/icons/lib/function_add_icons.php";

  // Icon retrieval
  $function["icons:get"][]= $CFG->dirroot . "mod/icons/lib/function_get_icon.php";

  // Permissions check
  $function["permissions:check"][]= $CFG->dirroot . "mod/icons/lib/permissions_check.php";

}
?>
