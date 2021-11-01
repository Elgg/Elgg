<?php

namespace Elgg\Database;

/**
 * Controls all admin notices in the system.
 *
 * @internal
 * @since 1.10.0
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
	 * @return \ElggAdminNotice|bool
	 */
	public function add(string $id, string $message) {
		if (!$id || !$message) {
			return false;
		}
		
		if (elgg_admin_notice_exists($id)) {
			return false;
		}

		$admin_notice = new \ElggAdminNotice();
		$admin_notice->admin_notice_id = $id;
		$admin_notice->description = $message;

		$result = elgg_call(ELGG_IGNORE_ACCESS, function() use($admin_notice) {
			// need to handle when no one is logged in
			return $admin_notice->save();
		});
		
		return $result ? $admin_notice : false;
	}
	
	/**
	 * Remove an admin notice by ID.
	 *
	 * @param string $id The unique ID assigned in add_admin_notice()
	 *
	 * @return bool
	 */
	public function delete(string $id = '') {
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
			$result = true;
			
			$notices = $this->find([
				'metadata_name' => 'admin_notice_id',
				'metadata_value' => $id,
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			
			// in case a bad plugin adds many, let it remove them all at once.
			foreach ($notices as $notice) {
				$result = ($result && $notice->delete());
			}
			
			return $result;
		});
	}
	
	/**
	 * Get admin notices. An admin must be logged in since the notices are private.
	 *
	 * @param array $options Query options
	 *
	 * @return \ElggObject[]|int|mixed Admin notices
	 */
	public function find(array $options = []) {
		$options = array_merge($options, [
			'type' => 'object',
			'subtype' => 'admin_notice',
		]);

		return Entities::find($options);
	}
	
	/**
	 * Check if an admin notice is currently active.
	 *
	 * @param string $id The unique ID used to register the notice.
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public function exists(string $id) {
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
			return (bool) elgg_count_entities([
				'type' => 'object',
				'subtype' => 'admin_notice',
				'metadata_name_value_pair' => ['name' => 'admin_notice_id', 'value' => $id],
			]);
		});
	}
}
