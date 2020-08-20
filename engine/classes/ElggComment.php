<?php
/**
 * \ElggComment
 *
 * @since 1.9.0
 */
class ElggComment extends \ElggObject {

	/**
	 * Set subtype to comment
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'comment';
	}

	/**
	 * Can a user comment on this object? Always returns false (threaded comments
	 * not yet supported)
	 *
	 * @see \ElggEntity::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 * @since 1.9.0
	 */
	public function canComment($user_guid = 0, $default = false) {
		return false;
	}
}
