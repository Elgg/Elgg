<?php

/**
 * Bookmark
 *
 * @property string $address URL of bookmark
 */
class ElggBookmark extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "bookmarks";
	}
}
