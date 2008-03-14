<?php

	/**
	 * Elgg access permissions
	 * For users, objects, collections and all metadata
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get the list of access restrictions the given user is allowed to see on this site
	 *
	 * @uses get_access_array
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return string A list of access groups suitable for injection in an SQL call
	 */
		function get_access_list($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_list;
			
			if (!isset($access_list))
				$access_list = array();
			
			if ($user_id == 0) $user_id = $_SESSION['id'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_list[$user_id]) || $flush == true) {
				
				$access_list[$user_id] = "(" . implode(",",get_access_array($user_id, $site_id, $flush)) . ")";
				
			}
			
			return $access_list[$user_id];
			
		}
		
	/**
	 * Gets an array of access restrictions the given user is allowed to see on this site
	 *
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return array An array of access groups suitable for injection in an SQL call
	 */
		function get_access_array($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_array;
			
			if (!isset($access_array))
				$access_array = array();
			
			if ($user_id == 0) $user_id = $_SESSION['id'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_array[$user_id]) || $flush == true) {
				
				$query = "select am.access_group_id from {$CONFIG->dbprefix}access_group_membership am ";
				$query .= " left join {$CONFIG->dbprefix}access_groups ag on ag.id = am.access_group_id ";
				$query .= " where am.user_guid = {$user_id} and (ag.site_guid = {$site_id} or ag.site_guid = 0)";
				
				$tmp_access_array = array(2);
				if (isloggedin())
					$tmp_access_array[] = 1;
				
				if ($groups = get_data($query)) {
					foreach($groups as $group)
						$tmp_access_array[] = $group->access_group_id;
				}
				
				$access_array[$user_id] = $tmp_access_array;
				
			}
			
			return $access_array[$user_id];
			
		}

	/**
	 * Some useful constant definitions
	 */
		define('PRIVATE',0);
		define('LOGGED_IN',1);
		define('PUBLIC',2);
?>