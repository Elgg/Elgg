<?php

	/**
	 * Elgg friends
	 * Functions for friendship management
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */


	/**
	 * Make a friend
	 * 
	 * @param $user_id int User ID of the user being befriended.
	 * @param $friend_id int User ID of the new friend.
	 *
	 * @return mixed record id if success,  False if attempt failed
	 */

	    function make_friend($user_id,$friend_id, $site_id)
	    {
	    	
	    	global $CONFIG;
	    	
	    	$user_id = (int) $user_id;
	    	$friend_id = (int) $friend_id;
	    	$site_id = (int) $site_id;
	        if (!get_data_row("SELECT id FROM {$CONFIG->dbprefix}friends WHERE user_id = $user_id AND friend_id = $friend_id AND site_id = {$site_id}")) {
	            return insert_data("INSERT INTO {$CONFIG->dbprefix}friends (user_id,friend_id,site_id) VALUES ($user_id,$friend_id,$site_id)");
	        } else {
	            return false;
	        }  
	
	    }
    
    /**
	 * Remove friend
	 * 
	 * @param $user_id int User ID of the user having the friend removed.
	 * @param $friend_id int User ID of the friend being removed.
	 */

	    function remove_friend($user_id,$friend_id,$site)
	    {
	        global $CONFIG;
	        $user_id = (int) $user_id;
	        $friend_id = (int) $friend_id;
	   		$site = (int) $site;
	        delete_record("DELETE FROM {$CONFIG->dbprefix}friends WHERE user_id = $user_id AND friend_id = $friend_id AND site_id = {$site}");
	        return true;
	    }
    
    /**
     * Determines if the user $user_id is friends with the currently logged in user, or a specified user
     * @param int $user_id The user we're testing to see if they're a friend
     * @param int $friend_of Optionally, the user we're testing to see if they're a friend of (the logged in user if unspecified)
     * @return true|false Wehther $user_id is a friend of $friend_of
     */
	    function is_friend($user_id, $friend_of, $site) {
	    	global $CONFIG;
	        $user_id = (int) $user_id;
	        $friend_of = (int) $friend_of;
	        $site = (int) $site;
	        if ($friend_of < 1) {
	            return false;
	        }
	        if (get_data_row("select id FROM {$CONFIG->dbprefix}friends where user_id = {$friend_of} and friend_id = {$user_id} and site_id = {$site}")) {
	            return true;
	        }
	        return false;
	    }

	/**
	 * Get a user's friends
	 *
	 * @param int $user_id The user ID
	 * @param int $site The site ID
	 * @return unknown
	 */
	    
	   function get_friends($user_id, $site) {
	   	
	   		global $CONFIG;
	   		$user_id = (int) $user_id;
	   		$site = (int) $site;
	   		return get_data("SELECT u.* FROM {$CONFIG->dbprefix}friends f LEFT JOIN {$CONFIG->dbprefix}users u on u.id = f.friend_id WHERE user_id = {$user_id} and site_id = {$site}","row_to_elgguser");
	   	
	   }
	    
    /**
     * Return friend IDs in a way suitable for including in a 'where' statement.
     *
     * @param unknown_type $user_id
     * @return unknown
     */
	    function get_friends_where($user_id, $site) {
	    	global $CONFIG;
	        $friendswhere = "";
	        if ($friends = get_friends($user_id,1,999,$site)) {
	        	$friendswhere .= $user_id;
	            foreach($friends as $friend) {
	                if (!empty($friendswhere)) $friendswhere .= ",";
	                $friendswhere .= $friend;
	            }
	        }
	        if (empty($friendswhere)) {
	            $friendswhere = "-1";
	        }
	        return "({$friendswhere})";
	    }
    
    /**
	 * Get all the people who have befriended friend_id
	 * 
	 * @param $friend_id int User ID of the user in question
	 * @param $degree int
	 *
	 * @return array of ElggUsers
	 */

	    function get_friends_reverse($friend_id, $site)
	    {
	    	global $CONFIG;
	    	$friend_id = (int) $friend_id;
	    	$site = (int) $site;
	        return get_data("SELECT u.* FROM {$CONFIG->dbprefix}friends f LEFT JOIN {$CONFIG->dbprefix}users u on u.id = f.user_id WHERE friend_id = {$friend_id} and site_id = {$site}","row_to_elgguser");
	    }
    
	/**
     * Returns a stirng of reverse friend IDs suitable for dropping into an SQL statement
     *
     * @param unknown_type $user_id
     * @return unknown
     */
    function get_friends_reverse_where($user_id, $site) {
        
        $friendswhere = "";
        if ($friends = get_friends_reverse($user_id,$site)) {
            $friendswhere .= $user_id;
            foreach($friends as $friend) {
                if (!empty($friendswhere)) $friendswhere .= ",";
                $friendswhere .= $friend;
            }
        }
        if (empty($friendswhere)) {
        	$friendswhere = "-1";
        }
        return "({$friendswhere})";
    }
    
    /**
     * Get all the mutual friends of $user_id
     * 
     * @param $user_id int User ID of the user in question
     * @param $site int Optionally, the ID of the current site
     *
     * @return array of ElggUsers
     */
    
	    function get_mutual_friends($user_id, $site) {
	    	
	    	global $CONFIG;
	    	
		    $friend_id = (int) $user_id;		    
		    $friends = get_friends_where($user_id, $site);
		    
		    return get_data("SELECT u.* FROM {$CONFIG->dbprefix}friends f LEFT JOIN {$CONFIG->dbprefix}users u on u.id = f.user_id WHERE friend_id = {$friend_id} and site_id = {$site} and user_id in {$friends}","row_to_elgguser");
	    	
	    }
    
    /**
     * Returns a stirng of mutual friend IDs suitable for dropping into an SQL statement
     *
     * @param unknown_type $user_id
     * @return unknown
     */
	    function get_mutual_friends_where($user_id, $site) {
	    	
	    	$friendswhere = "";
	        if ($friends = get_mutual_friends($user_id, $site)) {
	        	$friendswhere .= $user_id;
	            foreach($friends as $friend) {
	                if (!empty($friendswhere)) $friendswhere .= ",";
	                $friendswhere .= $friend;
	            }
	        }
	        return "({$friendswhere})";
	    	
	    }
    

?>