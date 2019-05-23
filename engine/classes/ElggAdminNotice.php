<?php
/**
 * Admin Notice
 */
class ElggAdminNotice extends \ElggObject {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'admin_notice';
		// admins can see ACCESS_PRIVATE but no one else can.
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
}
