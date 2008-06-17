<?php

    /**
     * Library of functions for user polling and manipulation.
     * 
     * @copyright Copyright (C) 2004-2006 Ben Werdmuller and David Tosh
     * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
     * @package elgg
     * @subpackage elgg.lib
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

	/**
	 * User information value by id
	 * 
	 * Returns a specified field by user id
	 * 
	 * @param string $fieldname the specific field
	 * @param integer $user_id the user id
	 * @return mixed string if success else false if the user doesn't exist
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
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
    
    /**
	 * User information value by username
	 * 
	 * Returns a specified field by username
	 *
	 * @param string $fieldname the specific field
	 * @param integer $user_id the user id
	 * @return mixed string if success else false if the user doesn't exist
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
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
    
    /**
     * Gets the type of a particular user
     * 
     * @param integer $user_id the user id
     * @return mixed string the user type or false if the user doesn't exist
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
    function user_type($user_id) {
        return user_info('user_type', $user_id);
    }
    
    /**
     * Returns a user's name, with event hooks allowing for interception.
     * Internally passes around a "user_name" "display" event, with an object
     * containing the elements 'name' and 'owner'.
     *
     * @uses $CFG
     * @param integer $user_id  The unique ID of the user we want to find the name for.
     * @return string Returns the user's name, or a blank string if something went wrong (eg the user didn't exist).
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
    function user_name($user_id) {
        global $CFG;
        $user_name = new stdClass;
        $user_name->owner = $user_id;
        if ($user_name->name = user_info("name",$user_id)) {
            if ($user_name = plugin_hook("user_name","display",$user_name)) {
                return $user_name->name;
            }
        }
        return "";
    }
     
    /**
     * Returns the HTML to display a user's icon, with event hooks allowing for interception.
     * Internally passes around a "user_icon" "display" event, with an object
     * containing the elements 'html', 'icon' (being the icon ID), 'size', 'owner' and 'url'.
     *
     * @todo TODO refactor, separate display code
     * @global CFG global configuration
     * @param integer $user_id  The unique ID of the user we want to display the icon for.
     * @param integer $size  The size of the icon we want to display (max: 100).
     * @param boolean $urlonly  If true, returns the URL of the icon rather than the full HTML.
     * @return string Returns the icon HTML, or the default icon if something went wrong (eg the user didn't exist).
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
    function user_icon_html($user_id, $size = 100, $urlonly = false) {
        global $CFG;
        $extra = "";
        $user_icon = new stdClass;
        $user_icon->owner = $user_id;
        $user_icon->size = $size;
        if ($size < 100) {
            $extra = "/h/$size/w/$size";
        }
        if ($user_icon->icon = user_info("icon",$user_id)) {
            $user_icon->url = "{$CFG->wwwroot}_icon/user/{$user_icon->icon}{$extra}";
	    $user_fullname = user_info("name",$user_id);
	    $user_icon->html = "<img src=\"{$user_icon->url}\" border=\"0\" alt=\"{$user_fullname}\" title=\"{$user_fullname}\" />";
            if ($user_icon = plugin_hook("user_icon","display",$user_icon)) {
                if ($urlonly) {
                    return $user_icon->url;
                } else {
                    return $user_icon->html;
                }
            }
        }
        if ($urlonly) {
            return -1;
        } else {
            return "<img src=\"{$CFG->wwwroot}_icon/user/-1{$extra}\" border=\"0\" alt=\"default user icon\" />";
        }
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
        //error_log($currentaccess);
            
        return $currentaccess;
    }
    
    // Protect users to a certain access level
    function protect($level, $owner = -1) {
        global $CFG;
        
        //error_log($level);
        //error_log($owner);
        if (accesslevel($owner) < $level) {
            echo '<a href="' . $CFG->wwwroot . '">' . __gettext("Access Denied") . '</a>';
            exit();
        }
    }

// NOTIFICATIONS AND MESSAGING /////////////////////////////////////////////////

    // 
    
    /**
     * Send a message to a user
     * 
     * @param integer $to the receiving user id
     * @param integer $from the sending user id
     * @param string $message the message body
     * @return boolean
     * @author Ben WerdMuller <ben@curverider.co.uk>
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
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

            if ($email_to = get_record('users', 'ident', $to))
            {
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
            trigger_error(__FUNCTION__.": Failed to send message from $from to $to. An unknown error occurred.", E_ERROR);
            
            $messages[] = __gettext("Failed to send message. An unknown error occurred.");
        } else {
            plugin_hook("message", "publish", $m);
            return true;
        }
        
    }
    
    /**
     * Get user's messages
     * 
     * Get the user's messages, optionally limit the number or the timeframe
     * 
     * @param integer $user_id the user id
     * @param integer $number the number of messages to retrieve
     * @param integer $timeframe the timeframe
     * @return mixed an ADODB RecordSet object with the results or false
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
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
    
    /**
     * Return the basic HTML for a message (given its database row), 
     * where the title is a heading 2 and the body is in a paragraph.
     * 
     * @param string $message the message body
     * @return string HTML output
     * @todo TODO refactor, separate display and logic
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */    
    function display_message($message) {
        
        global $CFG;
        
        if ($message->from_id == -1) {
            $from->name = __gettext("System");
        } else {
            $from = get_record_sql("select * from ".$CFG->prefix."users where ident = " . $message->from_id);
        }
        
        $title ="[" .  __gettext("Message from ");
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
	
    /**
     * Send a notification to a user
     * 
     * Could be a notifications or email, 
     * depending on a user's preferences
     * 
     * @param integer $user_id the user id
     * @param string $title the message title
     * @param string $message the message body
     * @return boolean
     * @todo TODO fix return type
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */    
    function notify_user($user_id, $title, $message) {
        
        message_user($user_id, -1, $title, $message);
    }
    
    /**
     * Mark a user's messages as read
     * 
     * @param integer $user_id the user's id
     * @return mixed An ADODB RecordSet object with the results from the SQL call or false.
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
    function messages_read($user_id) {
        
        global $CFG;

        $result = set_field('messages', 'status', 'read', 'to_id', $user_id);
        
        return $result;
    }
    
    /**
     * Clean up user messages
     * 
     * @global CFG global configuration
     * @param integer $older_than
     * @return boolean
     * @todo TODO this should be relatively temporary (Ben?)
     * @author Ben WerdMuller <ben@curverider.co.uk>
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
    function cleanup_messages($older_than) {
     
        global $CFG;
        
        $result = execute_sql("delete from ".$CFG->prefix."messages where posted < " . $older_than,false);
        
        return $result;
    }
    
// STATISTICS //////////////////////////////////////////////////////////////////

    /**
     * Count number of users
     * 
     * @global CFG global configuration 
     * @param string $type the user_type (eg 'person')
     * @param integer $last_action the minimum last time they performed an action
     * @return integer the number of users
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
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
            if (empty($type) || sizeof($users) > 1) {
                return $users;
            }
            foreach($users as $user) {
                return $user->numusers;
            }
        }
        
        return false;
    }

// USER DEATH //////////////////////////////////////////////////////////////////

    /**
     * Delete a user.
     *
     * @global CFG global configuration
     * @param integer $user_id  The unique ID of the user to delete.
     * @return true|false Returns true if the user was deleted; false otherwise.
     * @author Ben WerdMuller <ben@curverider.co.uk>
     */
    function user_delete($user_id) {

        global $CFG;

        // Verify that the user exists
        if ($user = get_record_sql("select * from {$CFG->prefix}users where ident = {$user_id}")) {
             
            // Call the event hook for all plugins to do their worst with the user's data
            $user = plugin_hook("user","delete",$user);
             
            // If all went well ...
            if (!empty($user)) {
                 
                // Remove any icons and icon folders
                if ($icons = get_records_sql("select * from {$CFG->prefix}icons where owner = {$user->ident}")) {
                    foreach($icons as $icon) {
                        $filepath = $CFG->dataroot . "icons/" . substr($user->username,0,1) . "/" . $user->username . "/" . $icon->filename;
                        @unlink($filepath);
                    }
                    @rmdir($filepath = $CFG->dataroot . "icons/" . substr($user->username,0,1) . "/" . $user->username . "/");
                }
                 
                // Remove the user from the database!
                delete_records("users","ident",$user->ident);
                delete_records("user_flags","user_id",$user->ident);
                delete_records("messages","to_id",$user->ident);
                delete_records("messages","from_id",$user->ident);
                return true;

            }
             
            return false;
        }
    }
    
    /**
     * Get the user's friends
     * 
     * @global CFG global configuration
     * @param integer $user_id the user id
     * @return mixed a result set packed in an array or empty
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
    function user_friends($user_id) {
        global $CFG;
        
        $result = get_records_sql('SELECT f.friend AS user_id,u.name FROM '.$CFG->prefix.'friends f
                                   JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                   WHERE f.owner = ? ORDER BY u.name', array($user_id));
        
        return $result;
    }

    /**
     * Get the users who have marked this user as a friend (sorted by the friends latest activity)
     * 
     * @global CFG global configuration
     * @param integer $user_id the user id
     * @return mixed a result set packed in an array or empty
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
    function user_friends_of($user_id) {
        global $CFG;
        
        $result = get_records_sql('SELECT u.ident, u.username FROM '.$CFG->prefix.'friends f
                                  JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                                  WHERE friend = ? AND u.user_type = ? order by u.last_action desc', array($user_id, 'person'));
        
        return $result;
    }
    
    /**
     * Add the a user to a friends list
     * 
     * @param integer $user_id the user id
     * @param integer $friend_id the friend id
     * @param boolean $moderate is this a moderated friendship request
     * @param string  $type the type, used for being able to reuse functionality for communities 
     * @return boolean
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
    function user_friend_add($user_id, $friend_id, $moderation = false, $type = 'friendship') {
        if (empty($user_id) || empty($friend_id)) {
            trigger_error(__FUNCTION__.": invalid arguments (user id: $user_id, friend id: $friend_id)", E_ERROR);
        }
        
        if (!record_exists('friends', 'owner', $user_id, 'friend', $friend_id)) {
            $obj         = new StdClass;
            $obj->owner  = $user_id;
            $obj->friend = $friend_id;

            // Type check
            if ($type != 'friendship' || $type != 'membership') {
                trigger_error("user_friend_add(): only type 'friendship' or 'membership' is accepted but '$type' was passed.", E_ERROR);
                return false;
            }
            
            if ($moderation == false) {
                // Regular friendship/membership
                if (insert_record('friends', $obj)) {
                    plugin_hook($type, "publish", $obj);
                    return true;
                } else {
                    return false;   
                }
            } else {
                // Moderated friendship/membership
                if (insert_record('friends_requests', $obj)) {
                    plugin_hook($type, "request", $obj);
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Delete a user from a friends list
     * 
     * @param integer $user_id the user id
     * @param integer $friend_id the friend id
     * @return mixed an ADODB RecordSet object with the results or false
     * @author Misja Hoebe <misja@curverider.co.uk>
     */
    function user_friend_delete($user_id, $friend_id) {
        if (empty($user_id) || empty($friend_id)) {
            trigger_error(__FUNCTION__.": invalid arguments (user id: $user_id, friend id: $friend_id)", E_ERROR);
        }
        
        $result = delete_records('friends', 'owner', $user_id, 'friend', $friend_id);
        
        if ($result != false) {
            $obj = new StdClass;
            $obj->owner = $user_id;
            $obj->friend = $friend_id;

            plugin_hook("friendship", "delete", $obj);
        }

        return $result;
    }
?>