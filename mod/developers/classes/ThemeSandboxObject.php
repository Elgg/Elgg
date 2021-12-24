<?php

/**
 * @internal
 */
class ThemeSandboxObject extends ElggObject {
	
	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'theme_sandbox';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getTimeCreated() {
		return time();
	}
}
