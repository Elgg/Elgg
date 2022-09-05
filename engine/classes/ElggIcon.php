<?php

use Elgg\Exceptions\LogicException as ElggLogicException;

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
	 * @throws \Elgg\Exceptions\LogicException
	 */
	public function save(): bool {
		throw new ElggLogicException(__CLASS__ . ' instances exist as placeholders and can not be upgraded to entities');
	}
}
