<?php
/**
 * ElggComment
 * 
 * @package    Elgg.Core
 * @subpackage Comments
 * @since      1.9.0
 */
class ElggComment extends ElggObject {

	/**
	 * Set subtype to comment
	 * 
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "comment";
	}

	/**
	 * Not supporting threaded comments yet
	 * 
	 * @return bool
	 */
	public function canComment() {
		return false;
	}

	/**
	 * Can a user edit this comment?
	 *
	 * @tip Can be overridden by registering for the permissions_check plugin hook.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this comment is editable by the given user.
	 * @see elgg_set_ignore_access()
	 */
	public function canEdit($user_guid = 0) {
		$user_guid = (int)$user_guid;
		$user = get_entity($user_guid);
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}

		// default is to only allow admins to edit.
		$return = ($user && $user->isAdmin());

		$params = array('entity' => $this, 'user' => $user);
		return elgg_trigger_plugin_hook('permissions_check', $this->type, $params, $return);
	}
}
