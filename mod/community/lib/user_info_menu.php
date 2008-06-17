<?php


/*
 * user_info_menu.php
 *
 * Created on Apr 30, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
global $profile_id;
global $CFG;

if (isset ($parameter) && is_object($parameter[0])) {
  $info = $parameter[0];
  $functions = array ();
  $membercount = run('community:members:count',$info->ident);
  $functions[] = "<a href=\"" . $CFG->wwwroot . $info->username . "/community/members\">" . __gettext("Members") . "&nbsp;(" . $membercount . ")</a>";

  if ($info->owner == $_SESSION['userid'] && $info->owner == $profile_id) {
    $functions[] = "<a href=\"" . $CFG->wwwroot . $info->username . "/profile\">" . __gettext("Administer") . "</a>";
    $functions[] = "<a href=\"" . $CFG->wwwroot . $info->username . "/community/delete\">" . __gettext("Delete") . "</a>";
    if ($profile_id != $_SESSION['userid']) {
      $msg = "onclick=\"return confirm('" . addslashes(__gettext("Are you sure you want to separate this user from the community?")) . "')\"";
      $functions[] = "<a href=\"" . $CFG->wwwroot . $info->username . "/community/separate/" . $profile_id . "\" $msg>" . __gettext("Separate") . "</a>";
    }
  }
  $run_result .= implode("\n", array_map(create_function('$entry', "return \"<li>\$entry</li>\";"), $functions));
}
?>
