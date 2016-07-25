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
}
