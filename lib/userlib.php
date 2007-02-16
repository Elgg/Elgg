<?php

/**
 * Library of functions for user polling and manipulation.
 * Largely taken from the old /units/users/
 * Copyright (C) 2004-2006 Ben Werdmuller and David Tosh
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

 
// INITIALISATION //////////////////////////////////////////////////////////////

    // TODO: These need somewhere else to live. They're to do with
    // authentication and session management, not user management.

    // Session variable name
    define('user_session_name', 'elgguser');
    
    // Persistent login cookie DEFs
    define('AUTH_COOKIE', 'elggperm');
    define('AUTH_COOKIE_LENGTH', 31556926); // 1YR in seconds
    
    // Messages
    define('AUTH_MSG_OK', __gettext("You have been logged on."));
    define('AUTH_MSG_BADLOGIN', __gettext("Unrecognised username or password. The system could not log you on, or you may not have activated your account."));
    define('AUTH_MSG_MISSING', __gettext("Either the username or password were not specified. The system could not log you on."));

// USER INFORMATION RETRIEVAL //////////////////////////////////////////////////

    // Given a user ID number, returns the specified field
    // Returns false if the user doesn't exist.
    function user_info($fieldname, $user_id) {
        
        // Name table
        static $id_to_name_table;

        // Returns field from a given ID

        $user_id = (int) $user_id;
        
        if (!empty($user_id)) {
            if (!isset($id_to_name_table[$user_id][$fieldname])) {
                //$id_to_name_table[$user_id][$fieldname] = get_field('users',$fieldname,'ident',$user_id);
                
                // this reduces number of db queries, but uses slightly more memory
                // due to adodb's recordset generation, it has both named and numeric array keys
                $id_to_name_table[$user_id] = (array) get_record('users','ident',$user_id);
            }
            if (isset($id_to_name_table[$user_id][$fieldname])) {
                return $id_to_name_table[$user_id][$fieldname];
            }
        }
        
        // If we've got here, the user didn't exist in the database
        return false;
        
    }
    
    // Given a username, returns the specified field
    // Returns false if the user doesn't exist.
    function user_info_username($fieldname, $username) {
        
        // Name table
        static $name_to_id_table;

        // Returns user's ID from a given name
        
        if (!empty($username)) {
            if (!isset($name_to_id_table[$username][$fieldname])) {
                //$name_to_id_table[$username][$fieldname] = get_field('users',$fieldname,'username',$username);
                $name_to_id_table[$username] = (array) get_record('users','username',$username);
            }
            if (isset($name_to_id_table[$username][$fieldname])){
                return $name_to_id_table[$username][$fieldname];
            }
        }
        
        // If we've got here, the user didn't exist in the database
        return false;
        
    }
    
    // Gets the type of a particular user
    function user_type($user_id) {
        
        return user_info('user_type', $user_id);
        
    }
    
// USER FLAGS //////////////////////////////////////////////////////////////////

    // Gets the value of a flag
    function user_flag_get($flag_name, $user_id) {
        if ($result = get_record('user_flags','flag',$flag_name,'user_id',$user_id)) {
            return $result->value;
        }
        return false;
    }
    
    // Removes a flag
    function user_flag_unset($flag_name, $user_id) {
        return delete_records('user_flags','flag',$flag_name,'user_id',$user_id);
    }
    
    // Adds a flag
    function user_flag_set($flag_name, $value, $user_id) {
        $flag_name = trim($flag_name);
        if ($flag_name) {
            // Unset the flag first
            user_flag_unset($flag_name, $user_id);
            
            // Then add data
            $flag = new StdClass;
            $flag->flag = $flag_name;
            $flag->user_id = $user_id;
            $flag->value = $value;
            return insert_record('user_flags',$flag);
        }
    }
    
// ACCESS RESTRICTIONS /////////////////////////////////////////////////////////

    // Get current access level
    // Utterly deprecated (user levels no longer work like this), but kept 
    // alive for now.
    function accesslevel($owner = -1) {
        $currentaccess = 0;

        // For now, there are three access levels: 0 (logged out), 1 (logged in) and 1000 (me)
        if (logged_on == 1) {
            $currentaccess++;
        }
            
        if ($_SESSION['userid'] == $owner) {
            $currentaccess += 1000;
        }
            
        return $currentaccess;
    }
    
    // Protect users to a certain access level
    function protect($level, $owner = -1) {
        global $CFG;
        if (accesslevel($owner) < $level) {
            echo '<a href="' . $CFG->wwwroot . '">' . __gettext("Access Denied") . '</a>';
            exit();
        }
    }

// NOTIFICATIONS AND MESSAGING /////////////////////////////////////////////////

    // Send a message to a user
    
    function message_user($to, $from, $title, $message) {
        
       global $messages, $CFG;
        
        if (isset($to->ident)) {
            $to = $to->ident;
        }
        
        $notifications = user_flag_get("emailnotifications",$to);
        if ($notifications) {
            $email_from = new StdClass;
            $email_from->email = $CFG->noreplyaddress;
            $email_from->name = $CFG->sitename;
            
            if ($email_to = get_record_sql("select * from ".$CFG->prefix."users where ident = " . $to)) {
            
                if (!email_to_user($email_to,$email_from,$title,$message . "\n\n\n" . __gettext("You cannot reply to this message by email."))) {
                    $messages[] = __gettext("Failed to send email. An unknown error occurred.");
                }
            }
        }
        
        $m = new StdClass;
        $m->title = $title;
        $m->body = $message;
        $m->from_id = $from;
        $m->to_id = $to;
        $m->posted = time();
        $m->status = 'unread';
        
        if (!insert_record('messages',$m)) {
            $messages[] = __gettext("Failed to send message. An unknown error occurred.");
        }
        
    }
    
    // Get user $user_id's messages; optionally limit the number or the timeframe
    
    function get_messages($user_id, $number = null, $timeframe = null) {
        
        global $CFG;
        
        $where = "";
        $limit = "";
        if ($number != null) {
            $limit = "limit $number";
        }
        if ($timeframe != null) {
            $where = " and posted > ". (time() - $timeframe);
        }
        
        return get_records_sql("select * from ".$CFG->prefix."messages where to_id = $user_id $where order by posted desc $limit");
        
    }
    
    // Return the basic HTML for a message (given its database row), where the 
    // title is a heading 2 and the body is in a paragraph.
    
    function display_message($message) {
        
        global $CFG;
        
        if ($message->from_id == -1) {
            $from->name = __gettext("System");
        } else {
            $from = get_record_sql("select * from ".$CFG->prefix."users where ident = " . $message->from_id);
        }
        
        $title = "[Message from ";
        if ($message->from_id != -1) {
            $title .= "<a href=\"" . $CFG->wwwroot . user_info("username",$message->from_id) . "/\">";
        }
        $title .= $from->name;
        if ($message->from_id != -1) {
            $title .= "</a>";
        }
        $title .= "] " . $message->title;
        $body = "<p>" . nl2br(str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",activate_urls($message->body))) . "</p>";
        
        $body = templates_draw(array(
                                        'context' => 'databox1',
                                        'name' => $title,
                                        'column1' => $body
                                      )
                                );
        
        return $body;
        
    }

    // Send a notification to a user, both using the notifications table and
    // - potentially - email, depending on a user's preferences
    
    function notify_user($user_id, $title, $message) {
        
        message_user($user_id, -1, $title, $message);
        
    }
    
    // Mark a user's messages as read
    
    function messages_read($user_id) {
        
        global $CFG;
        //execute_sql("update ".$CFG->prefix."messages set status = 'read' where to_id = $user_id",false);
        set_field('messages', 'status', 'read', 'to_id', $user_id);
        
    }
    
    // Cleanup messages - this should be relatively temporary
    
    function cleanup_messages($older_than) {
     
        global $CFG, $messages;
        execute_sql("delete from ".$CFG->prefix."messages where posted < " . $older_than,false);
        
           
    }
    
// STATISTICS //////////////////////////////////////////////////////////////////

    // Count number of users
    // Optional: the user_type (eg 'person') and the minimum last time they
    // performed an action
    
    function count_users($type = '', $last_action = 0) {
        
        global $CFG;
        
        $where = "1 = 1";
        if (!empty($type)) {
            $where .= " AND user_type = '$type'";
        }
        if ($last_action > 0) {
            $where .= " AND last_action > " . $last_action;
        }
        if ($users = get_records_sql('SELECT user_type, count(ident) AS numusers 
                                  FROM '.$CFG->prefix.'users
                                  WHERE '.$where.'
                                  GROUP BY user_type')) {
            if (sizeof($users) > 1) {
                return $users;
            }
            foreach($users as $user) {
                return $user->numusers;
            }
        }
        
        return false;
    }

?>