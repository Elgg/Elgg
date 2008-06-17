<?php
global $USER;
global $messages;

$action = optional_param('action');
if (!empty($action) && isloggedin()) {
    switch ($action) {
    case "content:flag":
        $cf = new StdClass;
        $cf->url = trim(optional_param('address','',PARAM_URL));
        if (!empty($cf->url)) {
            insert_record('content_flags',$cf);
            $messages[] = __gettext("You have flagged this page as being obscene or inappropriate. An administrator will investigate this shortly.");
        }
        break;
    }        

    if (user_flag_get("admin", $USER->ident)) {
        switch ($action) {
        case "content:flags:delete":
            $remove = optional_param('remove','',PARAM_URL);
            if (empty($remove)) {
                $remove = array();
            }
            if (!is_array($remove)) {
                $remove = array($remove);
            }
            foreach ($remove as $remove_url) {
                $remove_url = trim($remove_url);
                delete_records('content_flags','url',$remove_url);
            }
            $messages[] = "The selected content flags were deleted.";
            break;
        
            // Manage users
        case "userdetails:update":
            $id = optional_param('id',0,PARAM_INT);
            $change_username = trim(strtolower(optional_param('change_username','',PARAM_CLEAN)));
            $change_filequota = optional_param('change_filequota',0,PARAM_INT);
            $change_iconquota = optional_param('change_iconquota',0,PARAM_INT);
            if (!empty($id)) {
                if (!empty($change_username)) {
                    if (!validate_username($change_username)) {
                        $messages[] = __gettext("Error! The new username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                    } else {
                        $u = new StdClass;
                        $u->username = $change_username;
                        $u->ident = $id;
                        update_record('users',$u);
                        $messages[] = sprintf(__gettext("Username was changed to %s."),$change_username);
                    }
                }
                if (!empty($change_filequota)) {
                    $u = new StdClass;
                    $u->file_quota = $change_filequota;
                    $u->ident = $id;
                    update_record('users',$u);
                    $messages[] = sprintf(__gettext("File quota was changed to %d."),$change_filequota);
                }
                if (!empty($change_iconquota)) {
                    $u = new StdClass;
                    $u->icon_quota = $change_iconquota;
                    $u->ident = $id;
                    update_record('users',$u);
                    $messages[] = sprintf(__gettext("Icon quota was changed to %d."),$change_iconquota);
                }
                // Alter flags for users, including granting and denying admin access,
                // banning users etc
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
                    
            
            // Antispam save
        case "admin:antispam:save":
            $antispam = trim(optional_param('antispam'));
            $d = new StdClass;
            $d->name = 'antispam';
            $d->value = $antispam;
            delete_records('datalists','name','antispam');
            insert_record('datalists',$d);
            $messages[] = __gettext("Spam list updated.");
            break;
            
            // Add bulk users
        case "admin:users:add":
            // these are ARRAYS 
            $new_username = optional_param('new_username');
            $new_name = optional_param('new_name');
            $new_email = optional_param('new_email');
            if (is_array($new_username) && count($new_username) > 1
                && is_array($new_name) && count($new_name) > 1
                && is_array($new_email) && count($new_email) > 1) {
                
                global $admin_add_users;
                $admin_add_users = array();
                
                for ($i = 0; $i < 12; $i++) {
                    $ok = false;
                    
                    if ((!array_key_exists($i,$new_username) || empty($new_username[$i]))
                        || !array_key_exists($i,$new_name) || empty($new_name[$i])
                        || !array_key_exists($i,$new_email) || empty($new_email[$i])) {
                        continue;
                    }
                        
                    $new_username[$i] = trim(strtolower($new_username[$i]));
                    $new_name[$i] = trim($new_name[$i]);
                    $new_email[$i] = trim($new_email[$i]);
                    if (empty($new_username[$i]) || empty($new_name[$i]) || empty($new_email[$i])) {
                        $messages[] = sprintf(__gettext("User addition %d failed: at least one field was blank. Username: %s, name: %s, email: %s"),($i + 1),$new_username[$i],$new_name[$i],$new_email[$i]);
                        continue;
                    }
                    
                    if (!validate_username($new_username[$i])) {
                        $messages[] = sprintf(__gettext("New username %d (%s) was invalid; usernames must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.")
                                              ,($i + 1),$new_username[$i]);
                        continue;
                    } elseif (!username_is_available($new_username[$i])) {
                        $messages[] = sprintf(__gettext("User addition %d failed: username %s is already in use."),($i + 1),$new_username[$i]);
                        continue;
                    } 
                    if (!validate_email($new_email[$i])) {
                        $messages[] = sprintf(__gettext("User addition %d failed: email address %s appears to be invalid."),($i + 1),$new_email[$i]);
                        continue;
                    }
                    
                    $password = "";
                    // reset $j
                    $j = 0;
                    // add random characters to $password until $length is reached
                    while ($j < 8) { 
                        // pick a random character from the possible ones
                        $char = substr("abcdefghjkmnpqrstuvwxyz23456789", mt_rand(0, strlen("abcdefghjkmnpqrstuvwxyz23456789")-1), 1);
                        // we don't want this character if it's already in the password
                        if (!strstr($password, $char)) { 
                            $password .= $char;
                            $j++;
                        }
                    }
                    
                    $ok = true;
                    $md5password = md5($password);
                    
                    $u = new StdClass;
                    $u->username = $new_username[$i];
                    $u->name = $new_name[$i];
                    $u->email = $new_email[$i];
                    $u->password = $md5password;
                    $u->active = 'yes';
                    $u->user_type = 'person';
                    
                    $u = plugin_hook("user","create",$u);
                    
                    if (!empty($u)) {
                        $newid = insert_record('users',$u);
                        $u->ident = $newid;
    
                        // Calendar code shouldn't go here! But its here anyways so just checking
                        // the global function array to check to see if calendar module is loaded.
                        global $function;
                        if(isset($function["calendar:init"])) {
                            $c = new StdClass;
                            $c->owner = $newid;
                            insert_record('calendar',$c);
                        }

                        // adds "virtual" friend, so that user has at least one connection
                        $owner = 0;
                        $f = new StdClass;
                        $f->owner = $owner;
                        $f->friend = $newid;
                        insert_record('friends',$f);
                        $f->owner = $newid;
                        $f->friend = $owner;
                        insert_record('friends',$f);

                        $u = plugin_hook("user","publish",$u);
                        
                        $rssresult = run("weblogs:rss:publish", array($newid, false));
                        $rssresult = run("files:rss:publish", array($newid, false));
                        $rssresult = run("profile:rss:publish", array($newid, false));
                        $sitename = sitename;
                        $username = $new_username[$i];
                        email_to_user($u,null,sprintf(__gettext("Your new %s account"),sitename), 
                                      sprintf(__gettext("You have been added to %s!\n\nFor your records, your %s username and password are:\n\n\tUsername: %s\n\tPassword: %s\n\nYou can log in at any time by visiting %s and entering these details into the login form.\n\nWe hope you enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename,$sitename,$username,$password,url,$sitename));
                        $messages[] = sprintf(__gettext("User %s was created."),$username);
                    }
                }
            }
            break;
        }
    }
}
    

?>
