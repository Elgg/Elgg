<?php

namespace Elgg;

use Elgg\Exceptions\InvalidArgumentException;

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
	 * @throws InvalidArgumentException
	 */
	public function __construct($guid) {
		$guid = (int) $guid;

		if (!$guid || $guid < 1) {
			// Don't throw a ClassException to keep this class completely atomic.
			throw new InvalidArgumentException("GUIDs must be integers > 0.");
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
	public function getPath() {
		$bound = $this->getLowerBucketBound($this->guid);
		return "$bound/$this->guid/";
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
	 * @param int $guid        The guid to get a bound for. Must be > 0.
	 * @param int $bucket_size The size of the bucket. (The number of entities per dir.)
	 * @return int
	 */
	private static function getLowerBucketBound(int $guid, int $bucket_size = 0) {
		if ($bucket_size < 1) {
			$bucket_size = self::BUCKET_SIZE;
		}
		
		return (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
	}
}
