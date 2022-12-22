<?php

namespace Elgg;

use Elgg\Exceptions\RangeException;

/**
 * Locate the relative path of an entity's data dir.
 *
 * This returns paths like: '1/27/'.
 *
 * @note This class does not require the Elgg engine to be loaded and is suitable for
 *       being used directly.
 *
 * @internal
 */
class EntityDirLocator {

	/**
	 * Number of entries per matrix dir. DO NOT CHANGE!
	 */
	const BUCKET_SIZE = 5000;
	
	/**
	 * @var int
	 */
	protected $guid;

	/**
	 * Find an entity's data dir.
	 *
	 * @param int $guid GUID of the entity.
	 *
	 * @throws RangeException
	 */
	public function __construct(int $guid) {
		if ($guid < 1) {
			throw new RangeException('"guid" must be greater than 0');
		}

		$this->guid = $guid;
	}
	
	/**
	 * Construct a file path matrix for an entity.
	 * As of 1.9.0 matrixes are based on GUIDs and separated into dirs of 5000 entries
	 * with the dir name being the lower bound for the GUID.
	 *
	 * @return string The path with trailing '/' where the entity's data will be stored relative to the data dir.
	 */
	public function getPath(): string {
		$bound = $this->getLowerBucketBound($this->guid);
		
		return "{$bound}/{$this->guid}/";
	}

	/**
	 * String casting magic method.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getPath();
	}

	/**
	 * Return the lower bound for a guid with a bucket size
	 *
	 * @param int $guid The guid to get a bound for. Must be > 0.
	 *
	 * @return int
	 */
	private function getLowerBucketBound(int $guid): int {
		return (int) max(floor($guid / self::BUCKET_SIZE) * self::BUCKET_SIZE, 1);
	}
}
