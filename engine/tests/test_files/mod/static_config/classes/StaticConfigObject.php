<?php

class StaticConfigObject extends \ElggObject {
	
	/**
	 * @var string The subtype of this object
	 */
	const SUBTYPE = 'static_config_subtype';
	
	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
	}
}
