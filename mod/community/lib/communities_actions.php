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
                if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$comm_username)) {
                    $messages[] = __gettext("Error! The community username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                } else if (trim($comm_name) == "") {
                    $messages[] = __gettext("Error! The community name cannot be blank.");
                } else {
                    $comm_username = strtolower(trim($comm_username));
                    if (record_exists('users','username',$comm_username)) {
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
    
                        $f = new StdClass;
                        $f->owner = $USER->ident;
                        $f->friend = $cid;
                        insert_record('friends',$f);
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
             if (!empty($friend_id) && logged_on) {
                 if (user_info("user_type",$friend_id) == "community") {
                     if ($friend = get_record('users','ident',$friend_id)) {
                         $owner = get_record('users','ident',$friend->owner);
                         if ($friend->moderation == "no") {
                             $messages[] = sprintf(__gettext("You joined %s."), stripslashes($friend->name));
                             if (user_flag_get("emailnotifications",$owner->ident)) {
                                 $message_body = sprintf(__gettext("%s has joined %s!\n\nTo visit this user's profile, click on the following link:\n\n\t".
                                                                         "%s\n\nTo view all community members, click here:\n\n\t%s\n\nRegards,\n\nThe %s team."),
                                                                         $_SESSION['name'], $friend->name, $CFG->wwwroot . user_info("username",$USER->ident) . "/", $CFG->wwwroot . "_communities/members.php?owner=" . $friend_id,$CFG->sitename);
                                 $title = sprintf(__gettext("New %s member"), $friend->name);
                                 notify_user($owner->ident,$title,$message_body);
                             }
                         } else if ($friend->moderation == "yes") {
                             $messages[] = sprintf(__gettext("Membership of %s needs to be approved. Your request has been added to the list."), stripslashes($friend->name));
                             if (user_flag_get("emailnotifications",$owner->ident)) {
                                 $message_body = sprintf(__gettext("%s has applied to join %s!\n\nTo visit this user's profile, click on the following link:\n\n\t".
                                                                         "%s\n\nTo view all membership requests and approve or deny this user, click here:\n\n\t%s\n\nRegards,\n\nThe %s team."),
                                                                         $_SESSION['name'], $friend->name, $CFG->wwwroot . user_info("username",$USER->ident) . "/", $CFG->wwwroot . "_communities/members.php?owner=" . $friend_id,$CFG->sitename);
                                 $title = sprintf(__gettext("New %s member request"), $friend->name);
                                 notify_user($owner->ident,$title,$message_body);
                             }
                         } else if ($friend->moderation == "priv") {
                             $messages[] = sprintf(__gettext("%s is a private community. Your membership request has been declined."), stripslashes($friend->name));
                         }
                     }
                 }
             }
             break;
    
         // Unfriend someone
         case "unfriend":
             if (!empty($friend_id) && logged_on) {
                 if (user_type($friend_id) == "community") {
                     $name = user_info('username', $friend_id);
                     $messages[] = sprintf(__gettext("You left %s."), $name);
                 }
             }
             break;
    
        case "community:delete":
            $community_id = optional_param('community_id',0,PARAM_INT);
            if (run("permissions:check",array("userdetails:change", $community_id))) {
                if (user_delete($community_id)) {
                    // plugin_hook("community","publish",$community_id);
                    $messages[] = __gettext("The community was deleted.");
                } else {
                    $messages[] = __gettext("Error: the community could not be deleted.");
                }
                $_SESSION['messages'] = $messages;                
                header("Location: ".$CFG->wwwroot.$USER->username."/communities");
                exit;
            }
        break;
        case "separate":
          if(!empty($friend_id)){
            if(user_type($profile_id) == "community"){
              $name = user_info("username",$friend_id);
              if(delete_records("friends","owner",$friend_id,"friend",$profile_id)){
                $messages[] = sprintf(__gettext("%s was removed from your community"),$name);
              }
              else{
                $messages = sprintf(__gettext("%s coundn't be removed from your community"),$name);
              }
            }
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
                         if (insert_record('friends',$f)) {
                             delete_records('friends_requests','ident',$request_id);
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
    
    }
    
}
?>