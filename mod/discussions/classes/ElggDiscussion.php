<?php

/**
 * Discussion topic
 *
 * @property string $status The published status of the discussion (open|closed)
 */
class ElggDiscussion extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'discussion';
	}
}
