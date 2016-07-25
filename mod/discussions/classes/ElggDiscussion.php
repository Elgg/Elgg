<?php
/**
 * Discussion topic class
 */
class ElggDiscussion extends ElggObject {

	const SUBTYPE = 'discussion';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Override commenting permissions
	 *
	 * @param int  $user_guid User guid
	 * @param bool $default   Default permission
	 * @return boolean
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}
}
