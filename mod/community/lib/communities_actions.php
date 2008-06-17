<?php

global $CFG;
global $USER;
global $page_owner;
global $friend;
global $profile_id;

// Actions to perform on the friends screen
$action = optional_param('action');
$friend_id = optional_param('friend_id',0,PARAM_INT);

if (isloggedin()) {
    switch($action) {

        // Create a new community
        case "community:create":
            $comm_name = optional_param('comm_name');
            $comm_username = optional_param('comm_username');
            if (logged_on && !empty($comm_name) && !empty($comm_username) &&
                ($CFG->community_create_flag == "" || user_flag_get($CFG->community_create_flag, $USER->ident))) {
                if (!validate_username($comm_username)) {
                    $messages[] = __gettext("Error! The community username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                } else if (trim($comm_name) == "") {
                    $messages[] = __gettext("Error! The community name cannot be blank.");
                } else {
                    $comm_username = strtolower(trim($comm_username));
                    if (!username_is_available($comm_username)) {
                        $messages[] = sprintf(__gettext("The username %s is already taken by another user. You will need to pick a different one."), $comm_username);
                    } else {
                        $name = trim($comm_name);
                        $c = new StdClass;
                        $c->name = $name;
                        $c->username = $comm_username;
                        $c->user_type = 'community';
                        $c->owner = $USER->ident;
                        $cid = insert_record('users',$c);
                        $c->ident = $cid;

                        $rssresult = run("weblogs:rss:publish", array($cid, false));
                        $rssresult = run("files:rss:publish", array($cid, false));
                        $rssresult = run("profile:rss:publish", array($cid, false));

                        plugin_hook("community","publish",$c);
                        $messages[] = __gettext("Your community was created and you were added as its first member.");
                        $_SESSION['messages'] = $messages;
                        header("Location: " . $CFG->wwwroot."profile/edit.php?profile_id=".$cid);
                        exit;
                    }
                }
            }

            // There is deliberately not a break here - creating a community should automatically make you a member.

        // Friend someone
         case "friend":
             $_messages = run('community:member:add',array($friend_id));
             $messages = array_merge($messages,$_messages);
             break;

         // Unfriend someone
         case "unfriend":
             $_messages = run('community:member:remove',array($friend_id));
             $messages = array_merge($messages,$_messages);
             break;

        case "community:delete":
            $community_id = optional_param('community_id',0,PARAM_INT);
	    if($community_id == 0) {
	      $profile_name = optional_param('profile_name','');
	      $community_id = user_info_username('ident',$profile_name);
	    }
	    $community_name = htmlspecialchars(user_name($community_id), ENT_COMPAT, 'utf-8');

            require_confirm(__gettext('Are you sure you want to delete this community?'));

            if (run("permissions:check",array("userdetails:change", $community_id))) {
                if (user_delete($community_id)) {
                    // plugin_hook("community","publish",$community_id);
                    $messages[] = __gettext("The community was deleted.");
                } else {
                    $messages[] = __gettext("Error: the community could not be deleted.");
                }
                header_redirect($CFG->wwwroot.$USER->username.'/communities');
            }
        break;

        case "leave":
          $community_name = user_info('name',$profile_id);
        case "separate":
          if(!empty($friend_id)){
             $_messages = run('community:member:remove',array($friend_id,$action,$profile_id));
             $messages = array_merge($messages,$_messages);
          }
        break;

        case "weblogs:post:add":
            if (user_type($page_owner) == "community") {
                $messages[] = __gettext("Your post has been added to the community weblog.");
            }
            break;

            // Approve a membership request
        case "community:approve:request":
             $request_id = optional_param('request_id',0,PARAM_INT);
             if (!empty($request_id) && logged_on && user_type($page_owner) == "community") {
                 if ($request = get_record_sql('SELECT u.name,fr.owner,fr.friend FROM '.$CFG->prefix.'friends_requests fr LEFT JOIN '.$CFG->prefix.'users u ON u.ident = fr.owner
                                                WHERE fr.ident = ?',array($request_id))) {
                     if (run("permissions:check",array("userdetails:change", $page_owner))) {
                         $f = new StdClass;
                         $f->owner = $request->owner;
                         $f->friend = $request->friend;
                         if ($f->ident = insert_record('friends',$f)) {
                             delete_records('friends_requests','ident',$request_id);
                             plugin_hook("community:member","publish",$f);
                             $messages[] = sprintf(__gettext("You approved the membership request. %s is now a member of this community."),stripslashes($request->name));
                         } else {
                             $messages[] = __gettext("An error occurred: the membership request could not be approved.");
                         }
                     } else {
                         $messages[] = __gettext("Error: you do not have authority to modify this membership request.");
                     }
                 } else {
                     $messages[] = __gettext("An error occurred: the membership request could not be found.");
                 }

             }
             break;

             // Reject a membership request
         case "community:decline:request":
             $request_id = optional_param('request_id',0,PARAM_INT);
             if (!empty($request_id) && logged_on && user_type($page_owner) == "community") {
                 if ($request = get_record_sql('SELECT u.name,fr.owner,fr.friend FROM '.$CFG->prefix.'friends_requests fr LEFT JOIN '.$CFG->prefix.'users u ON u.ident = fr.owner
                                                WHERE fr.ident = ?',array($request_id))) {
                     if (run("permissions:check",array("userdetails:change", $page_owner))) {
                         delete_records('friends_requests','ident',$request_id);
                         $messages[] = sprintf(__gettext("You declined the membership request. %s is not a member of this community."),stripslashes($request->name));
                     } else {
                         $messages[] = __gettext("Error: you do not have authority to modify this membership request.");
                     }
                 } else {
                     $messages[] = __gettext("An error occurred: the membership request could not be found.");
                 }

             }
             break;

         case "community:approve:invitation":
         $request_id = optional_param('request_id',0,PARAM_INT);
         if (!empty($request_id) && logged_on && user_type($page_owner) == "person") { 
             if ($request = get_record_sql('SELECT u.name, fr.owner, fr.friend FROM '.$CFG->prefix.'friends_requests fr
                                    LEFT JOIN '.$CFG->prefix.'users u ON u.ident = fr.owner 
                                    WHERE fr.ident = ?',array($request_id))) {
                 if (run("permissions:check",array("userdetails:change", $page_owner))) {
                     $f = new StdClass;
                     $f->owner = $request->friend;
                     $f->friend = $request->owner;
                     if (insert_record('friends',$f)) {
                         delete_records('friends_requests','ident',$request_id);
                         $messages[] = sprintf(__gettext("You accepted the community membership invitation. You are now a member of the community %s."),stripslashes($request->name));
                         $message_body = sprintf(__gettext("%s has approved your community membership invitation!\n\nRegards,\n\nThe %s team."),user_name($request->friend), $CFG->sitename);
                         $title = __gettext("Community invitation accepted!");
                         notify_user($request->owner,$title,$message_body);
                     } else {
                         $messages[] = __gettext("An error occurred: couldn't add you as a community member");
                     }
                 } else {
                     $messages[] = __gettext("Error: you do not have authority to accept this community membership invitation.");
                 }
             } else {
                 $messages[] = __gettext("An error occurred: the community membership invitation could not be found.");
             }
             
         }
         break;
     case "community:decline:invitation":
         $request_id = optional_param('request_id',0,PARAM_INT);
         if (!empty($request_id) && logged_on && user_type($page_owner) == "person") {
             if ($request = get_record_sql('SELECT u.name, fr.owner, fr.friend FROM '.$CFG->prefix.'friends_requests fr
                                    LEFT JOIN '.$CFG->prefix.'users u ON u.ident = fr.owner 
                                    WHERE fr.ident = ?',array($request_id))) {
                 if (run("permissions:check",array("userdetails:change", $page_owner))) {
                     delete_records('friends_requests','ident',$request_id);
                     $messages[] = sprintf(__gettext("You declined the community membership invitation. You are not a member of community %s."),stripslashes($request->name));
                     $message_body = sprintf(__gettext("%s has denied your community membership invitation.\n\nRegards,\n\nThe %s team."),user_name($request->friend), $CFG->sitename);
                     $title = sprintf(__gettext("%s friend request denied"), $CFG->sitename);
                     notify_user($request->owner,$title,$message_body);
                 } else {
                     $messages[] = __gettext("Error: you do not have authority to modify this community membership invitation.");
                 }
             } else {
                 $messages[] = __gettext("An error occurred: the community membership invitation could not be found.");
             }
             
         }
         break;
    }

}
?>
