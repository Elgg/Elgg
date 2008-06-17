<?php
/*
 * user_info_menu.php
 *
 * Created on Apr 16, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
 global $profile_id;

 if (isset($parameter) && (isset($parameter[0])) && logged_on && ($profile_id == $_SESSION['userid'])) {
  $user_id = (int) $parameter[0];
  $friends_of=(count($parameter) == 2 && $parameter[1]=="friendsof")?true:false;

  if(!$friends_of){
    $run_result = "<li><a href=\"".url."mod/friend/index.php?friends_name=".$_SESSION['username']."&amp;action=unfriend&amp;friend_id=$user_id\" onclick=\"return confirm('". __gettext("Are you sure you want to remove this user from your friends list?") ."')\">" . __gettext("Remove"). "</a></li>";
  }
 }
?>
