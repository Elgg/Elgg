<?php

namespace Elgg\Export;

use Elgg\I18n\DateTime;
use Elgg\Values;

/**
 * Entity export representation
 *
 * @property int    $guid
 * @property string $type
 * @property string $subtype
 * @property int    $owner_guid
 * @property int    $container_guid
 * @property string $time_updated
 * @property string $url
 * @property int    $read_access
 */
class Entity extends Data {

	/**
	 * Get updated tme
	 *
	 * @return DateTime|null
	 */
	public function getTimeUpdated() {
		if (!$this->time_updated) {
			return null;
		}

		return Values::normalizeTime($this->time_created);
	}
}
