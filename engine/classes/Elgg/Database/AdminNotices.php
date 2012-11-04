<?php
namespace Elgg\Database;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Controls all admin notices in the system.
 * 
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class AdminNotices {
	/**
	 * Write a persistent message to the admin view.
	 * Useful to alert the admin to take a certain action.
	 * The id is a unique ID that can be cleared once the admin
	 * completes the action.
	 *
	 * eg: add_admin_notice('twitter_services_no_api',
	 * 	'Before your users can use Twitter services on this site, you must set up
	 * 	the Twitter API key in the <a href="link">Twitter Services Settings</a>');
	 *
	 * @param string $id      A unique ID that your plugin can remember
	 * @param string $message Body of the message
	 *
	 * @return bool
	 */
	function add($id, $message) {
		if ($id && $message) {
			if (elgg_admin_notice_exists($id)) {
				return false;
			}
	
			// need to handle when no one is logged in
			$old_ia = elgg_set_ignore_access(true);
	
			$admin_notice = new \ElggObject();
			$admin_notice->subtype = 'admin_notice';
			// admins can see ACCESS_PRIVATE but no one else can.
			$admin_notice->access_id = ACCESS_PRIVATE;
			$admin_notice->admin_notice_id = $id;
			$admin_notice->description = $message;
	
			$result = $admin_notice->save();
	
			elgg_set_ignore_access($old_ia);
	
			return (bool)$result;
		}
	
		return false;
	}
	
	/**
	 * Remove an admin notice by ID.
	 *
	 * eg In actions/twitter_service/save_settings:
	 * 	if (is_valid_twitter_api_key()) {
	 * 		delete_admin_notice('twitter_services_no_api');
	 * 	}
	 *
	 * @param string $id The unique ID assigned in add_admin_notice()
	 *
	 * @return bool
	 */
	function delete($id) {
		if (!$id) {
			return false;
		}
		$result = true;
		$notices = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'admin_notice_id',
			'metadata_value' => $id,
			'distinct' => false,
		));
	
		if ($notices) {
			// in case a bad plugin adds many, let it remove them all at once.
			foreach ($notices as $notice) {
				$result = ($result && $notice->delete());
			}
			return $result;
		}
		return false;
	}
	
	/**
	 * Get admin notices. An admin must be logged in since the notices are private.
	 *
	 * @param int $limit Limit
	 *
	 * @return array Array of admin notices
	 */
	function find($limit = 10) {
		return elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'admin_notice',
			'limit' => $limit,
			'distinct' => false,
		));
	}
	
	/**
	 * Check if an admin notice is currently active.
	 *
	 * @param string $id The unique ID used to register the notice.
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	function exists($id) {
		$old_ia = elgg_set_ignore_access(true);
		$notice = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'admin_notice',
			'metadata_name_value_pair' => array('name' => 'admin_notice_id', 'value' => $id),
			'distinct' => false,
		));
		elgg_set_ignore_access($old_ia);
	
		return ($notice) ? true : false;
	}
}