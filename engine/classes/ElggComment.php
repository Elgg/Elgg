<?php
/**
 * ElggComment
 * 
 * @package    Elgg.Core
 * @subpackage Comments
 * @since      1.9.0
 */
class ElggComment extends ElggObject {

	/** @override */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "comment";
	}
}
