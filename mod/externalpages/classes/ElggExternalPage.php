<?php

/**
 * Custom class for external pages
 *
 * @since 6.3
 */
class ElggExternalPage extends \ElggObject {
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['access_id'] = ACCESS_PUBLIC;
	}
}
