<?php
/**
 * ElggWire Class
 *
 * @property string $method      The method used to create the wire post (site, sms, api)
 * @property bool   $reply       Whether this wire post was a reply to another post
 * @property int    $wire_thread The identifier of the thread for this wire post
 */
class ElggWire extends ElggObject {

	/**
	 * Set subtype to thewire
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'thewire';
	}

	/**
	 * Can a user comment on this wire post?
	 *
	 * @see ElggObject::canComment()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		$result = parent::canComment($user_guid);
		if ($result == false) {
			return $result;
		}

		return false;
	}

}
