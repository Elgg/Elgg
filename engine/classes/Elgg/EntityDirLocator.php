<?php
/**
 * Locate the relative path of an entity's data dir.
 *
 * This returns paths like: '1/27/'.
 *
 * @note This class does not require the Elgg engine to be loaded and is suitable for
 *       being used directly.
 */

class Elgg_EntityDirLocator {

	/**
	 * Number of entries per matrix dir.
	 */
	const BUCKET_SIZE = 5000;

	/**
	 * Find an entity's data dir.
	 * 
	 * @param int $guid GUID of the entity.
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
	 * As of 1.8.5 matrixes are based on GUIDs and separated into dirs of 5000 entries
	 * with the dir name being the lower bound for the GUID.
	 *
	 * @param int $guid The guid of the entity to store the data under.
	 *
	 * @return str The path where the entity's data will be stored relative to the data dir.
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
	 * @param int $bucket_size The size of the bucket. (The number of entries per dir.)
	 * @return float
	 */
	private static function getLowerBucketBound($guid, $bucket_size = null) {
		if (!$bucket_size || $bucket_size < 1) {
			$bucket_size = self::BUCKET_SIZE;
		}
		if ($guid < 1) {
			return false;
		}
		return (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
	}
}