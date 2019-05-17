<?php

namespace Elgg\Export;

use ArrayObject;
use DateTime;

/**
 * Exported representation of an ElggData instance
 *
 * @property string $time_created
 */
abstract class Data extends ArrayObject {

	/**
	 * {@inheritdoc}
	 */
	public function __construct($input = [], int $flags = ArrayObject::ARRAY_AS_PROPS, string $iterator_class = "ArrayIterator") {
		parent::__construct($input, $flags, $iterator_class);
	}

	/**
	 * Get time created
	 * @return DateTime|null
	 */
	public function getTimeCreated() {
		if (!$this->time_created) {
			return null;
		}

		return new DateTime($this->time_created);
	}
}
