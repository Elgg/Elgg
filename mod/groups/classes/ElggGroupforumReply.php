<?php
/**
 * Class for group forum reply
 */
class ElggGroupforumReply extends ElggObject {

	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "groupforumreply";
	}
}
