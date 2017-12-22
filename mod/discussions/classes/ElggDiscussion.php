<?php

/**
 * Discussion topic
 *
 * @property string $status The published status of the blog post (published, draft)
 */
class ElggDiscussion extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "discussion";
	}
}
