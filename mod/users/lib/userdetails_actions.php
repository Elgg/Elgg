<?php

// Userdetails actions
global $USER, $CFG;
global $page_owner;
global $messages;

$id = optional_param('id',0,PARAM_INT);
$action = optional_param('action');

// check permissions on both idents,
// as $id and $page_owner are used in actions
if (logged_on && !empty($action)
    && run("permissions:check", array("userdetails:change",$id))
    && run("permissions:check", array("userdetails:change",$page_owner))) {
    
    switch ($action) {
    
    // Update user details
    case "user:delete":
            require_confirm(__gettext('Are you sure you want to delete this account?'), array('profile_id' => $page_owner, 'action' => 'user:delete'));

            if (user_delete($page_owner)) {
                if ($_SESSION['userid'] == $page_owner) {
                    $messages[] = __gettext("You have successfully deleted your account.");
                } else {
                    $messages[] = __gettext("The user was deleted.");
                }
            } else {
                $messages[] = __gettext("Error: the user could not be deleted.");
            }
            $_SESSION['messages'] = $messages;
            if ($_SESSION['userid'] == $page_owner) {
                header("Location: {$CFG->wwwroot}login/logout.php");
                exit;
            } else {
                header("Location: {$CFG->wwwroot}_userdetails/");
                exit;
            }
        break;
    case "userdetails:update":
        
        $usertype = user_type($page_owner);
        
        $name = trim(optional_param('name'));
        if (!empty($name)) {
            if (strlen($name) > 64) {
                $messages[] = __gettext("Your suggested name was too long. Please try something shorter.");
            } elseif ($name != $USER->name) {
                $u = new StdClass;
                $u->name = $name;
                $u->ident = $id;
                
                // Add a callback
	            $u = plugin_hook('userdetails', 'republish', $u);
	            if (empty($u)) {
	                trigger_error("The callback didn't receive a return value.", E_USER_ERROR);
	            }
                
                update_record('users',$u);
                
                if ($USER->ident == $page_owner) {
                    $USER->name = $name;
                    $_SESSION['name'] = $name;
                }
                $messages[] = __gettext("Name updated.");
            }
        }
        
        $email = trim(optional_param('email'));
        if ($usertype == 'person' && !empty($email)) {
            if (!validate_email($email)) {
                $messages[] = __gettext("Your suggested email address $email doesn't appear to be valid.");
            } elseif ($email != $USER->email) {
                $u = new StdClass;
                $u->email = $email;
                $u->ident = $id;
                
                // Add a callback
	            $u = plugin_hook('userdetails', 'republish', $u);

            	if (empty($u)) {
	                trigger_error("The callback didn't receive a return value.", E_USER_ERROR);
	            }
	            
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
        
        if (empty($CFG->disable_passwordchanging)) {
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
                        $u->password = $password1;
                        $u->ident = $page_owner;

                        // Add a callback
	                    $u = plugin_hook('userdetails', 'republish', $u);

	                    if (empty($u)) {
	                        trigger_error("The callback didn't receive a return value.", E_USER_ERROR);
	                    }
	                    
	                    // md5 the password
	                    $u->password = md5($u->password);
	                    
                        update_record('users',$u);
                    }
                } else {
                    $messages[] = __gettext("Password not changed: The password and its verification string did not match.");
                }
            }
        }
        
        // And process other flags
        // MNH - hack, check for admin, because the same operation 
		// gets performed in /units/admin/admin_userdetails.php
        if (!user_flag_get("admin", $USER->ident)) {
            $flags = optional_param('flag');
            if (empty($flags)) {
                $flags = array();
            }
            if (!is_array($flags)) {
                $flags = array($flags);
            }
            foreach ($flags as $flag => $value) {
                $flag = trim($flag);
                $value = trim($value);
                user_flag_set($flag, $value, $id);
                $messages[] = sprintf(__gettext("User flag '%s' set to '%s'"), $flag, $value);
            }
        }
        
        break;
    }
    
}
?>