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
	 *
	 * @throws \LogicException
	 */
	public function save() : bool {
		throw new \LogicException(__CLASS__ . ' instances exist as placeholders and can not be upgraded to entities');
	}
}
