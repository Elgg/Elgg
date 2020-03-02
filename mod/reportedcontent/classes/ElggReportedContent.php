<?php

/**
 * Report
 *
 * @property string $address URL of content
 * @property string $state   State of report. "active" or "archived"
 */
class ElggReportedContent extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'reported_content';
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
}
