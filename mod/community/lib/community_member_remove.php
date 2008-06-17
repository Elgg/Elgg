<?php
/*
 * community_member_remove.php
 *
 * Created on May 7, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
global $USER;
global $CFG;

if (isset ($parameter)) {
  $friend_id= $parameter[0];
  $action= (count($parameter) >= 2) ? $parameter[1] : "";
  $user_id= (count($parameter) >= 3) ? $parameter[2] : $USER->ident;

  error_log("Elimiminando con default!");
  $run_result= array ();
  if (!empty ($friend_id) && logged_on) {
    switch ($action) {
      case "leave" :
      case "separate" :
        $community_name= user_info('username', $user_id);
        $f = get_record('friends','owner',$friend_id,'friend',$user_id);
        if (delete_records("friends", "owner", $friend_id, "friend", $user_id)) {
          plugin_hook("community:member","delete",$f); 
          if ($action == "leave") {
            $run_result[]= sprintf(__gettext("You left %s"), $community_name);
            $_SESSION['messages']= $run_result;
            header("Location: " . $CFG->wwwroot . user_info('username', $user_id) . "/");
            exit;
          } else {
	    $friend_name = user_info('username', $friend_id); 
	    $run_result[]= sprintf(__gettext("%s was removed from your community"), $friend_name); 
          }
        } else {
          if ($action == "leave") {
            $run_result[]= sprintf(__gettext("An error occured: you couldn't leave %s"), $community_name);
          } else {
            $run_result[]= sprintf(__gettext("An error occured: %s couldn't be removed from your community"), $community_name);
          }
        }
        break;
      default :
        $community_name= user_info('username', $friend_id);
        $run_result[]= sprintf(__gettext("You left %s."), $community_name);
    }
  }
}
?>
