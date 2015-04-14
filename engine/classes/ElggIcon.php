<?php

/**
 * Entity icon class
 */
class ElggIcon extends ElggFile {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'icon';
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		throw new \LogicException(__CLASS__ . ' instances exist as placeholders and can not be upgraded to entities');
	}
}
