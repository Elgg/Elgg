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
}
