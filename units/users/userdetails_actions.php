<?php

// Userdetails actions
global $USER, $CFG;
global $page_owner;

$id = optional_param('id',0,PARAM_INT);
$action = optional_param('action');

if (logged_on && !empty($action) && run("permissions:check", array("userdetails:change",$id))) {
    
    switch ($action) {
        
        // Update user details
    case "userdetails:update":
        $name = trim(optional_param('name'));
        if (!empty($name)) {
            $userdetails_ok = "yes";
            if (strlen($name) > 64) {
                $messages[] = __gettext("Your suggested name was too long. Please try something shorter.");
                $userdetails_ok = "no";
            }
            
            $usertype = user_type($page_owner);
            $email = trim(optional_param('email'));
            if ($usertype == 'person' && !empty($email)) {
                if (!validate_email($email)) {
                    $messages[] = __gettext("Your suggested email address $email doesn't appear to be valid.");
                    
                    $userdetails_ok = "no";
                } else {
                    $u = new StdClass;
                    $u->email = $email;
                    $u->ident = $id;
                    update_record('users',$u);
                    if ($USER->ident == $page_owner) {
                        $USER->email = $email;
                        $_SESSION['email'] = $email;
                    }
                    $messages[] = __gettext("Email address updated.");
                }
            }
            
            $moderation = optional_param('moderation');
            if (!empty($moderation) && in_array($moderation,array('yes','no','priv'))) {
                set_field('users','moderation',$moderation,'ident',$id);
                $messages[] = __gettext("Your moderation preferences have been changed.");
            }

            if (!$CFG->disable_publiccomments) {
                $publiccomments = optional_param('publiccomments');
                if ($usertype == 'person' && !empty($publiccomments)) {
                    if ($publiccomments == "yes") {
                        user_flag_set("publiccomments", "1", $id);
                        $messages[] = __gettext("Public comments and discussion set to 'on'.");
                    } else {
                        user_flag_unset("publiccomments",$id);
                        $messages[] = __gettext("Public comments and discussion set to 'off'.");
                    }
                }
            }
            
            $receiveemails = optional_param('receiveemails');
            if ($usertype == 'person' && isset($receiveemails)) {
                if ($receiveemails == "yes") {
                    user_flag_set("emailreplies", "1", $id);
                    $messages[] = __gettext("Email comments and discussion set to 'on'.");
                } else {
                    user_flag_unset("emailreplies",$id);
                    $messages[] = __gettext("Email comments and discussion set to 'off'.");
                }
            }
            
            $receiveemails = optional_param('receivenotifications');
            if ($usertype == 'person' && isset($receiveemails)) {
                if ($receiveemails == "yes") {
                    user_flag_set("emailnotifications", "1", $id);
                    $messages[] = __gettext("Email notifications set to 'on'.");
                } else {
                    user_flag_unset("emailnotifications",$id);
                    $messages[] = __gettext("Email notifications set to 'off'.");
                }
            }
            
            if ($userdetails_ok == "yes") {
                $messages[] = "Name updated.";
                $u = new StdClass;
                $u->name = $name;
                $u->ident = $id;
                update_record('users',$u);
                if ($USER->ident == $page_owner) {
                    $USER->name = stripslashes($name);
                    $_SESSION['name'] = stripslashes($name);
                }
            } else {
                $messages[] = __gettext("Details were not changed.");
            }
            
        }

        $password1 = optional_param('password1');
        $password2 = optional_param('password2');

        if (!empty($password1) || !empty($password2)) {
            if (($password1 == $password2)) {
                if (strlen($password1) < 4 || strlen($password1) > 32) {
                    $messages[] = __gettext("Password not changed: Your password is either too short or too long. It must be between 4 and 32 characters in length.");
                } else if (!preg_match("/^[a-zA-Z0-9]*$/i",$password1)) {
                    $messages[] = __gettext("Password not changed: Your password can only consist of letters or numbers.");
                } else {
                    $messages[] = __gettext("Your password was updated.");
                    $u = new StdClass;
                    $u->password = md5($password1);
                    $u->ident = $page_owner;
                    update_record('users',$u);
                }
            } else {
                $messages[] = __gettext("Password not changed: The password and its verification string did not match.");
            }
        }
        break;
        
    }
    
}
?>