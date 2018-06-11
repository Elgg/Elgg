<?php

/**
 * Message
 *
 * @property int $toId       Recipient GUID
 * @property int $fromId     Sender GUID
 * @property int $readYet    Has the message been read? 1 = yes
 * @property int $hiddenFrom Has the user deleted the message from their sentbox?
 * @property int $hiddenTo   Has the user deleted the message from their inbox?
 */
class ElggMessage extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "messages";
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		if (!isset($default)) {
			$default = false;
		}
		
		return parent::canComment($user_guid, $default);
	}
	
	/**
	 * Get the recipient of the message
	 *
	 * @return ElggUser|false
	 */
	public function getRecipient() {
		return get_user($this->toId);
	}
	
	/**
	 * Get the sender of the message
	 *
	 * @return ElggUser|false
	 */
	public function getSender() {
		return get_user($this->fromId);
	}
}
