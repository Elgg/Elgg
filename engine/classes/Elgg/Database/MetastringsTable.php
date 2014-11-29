<?php
namespace Elgg\Database;

/** Cache metastrings for a page */
/**
 * @var string[] $METASTRINGS_CACHE
 * @access private
 */
global $METASTRINGS_CACHE;
$METASTRINGS_CACHE = array();


/**
 * Normalization for strings used in metadata and annoations tables.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 *
 * @access private
 */
class MetastringsTable {
	/**
	 * Gets the metastring identifier for a value.
	 *
	 * Elgg normalizes the names and values of annotations and metadata. This function
	 * provides the identifier used as the index in the metastrings table. Plugin
	 * developers should only use this if denormalizing names/values for performance
	 * reasons (to avoid multiple joins on the metastrings table).
	 *
	 * @param string $string         The value
	 * @param bool   $case_sensitive Should the retrieval be case sensitive?
	 *                               If not, there may be more than one result
	 *
	 * @return int|array metastring id or array of ids
	 */
	function getId($string, $case_sensitive = true) {
		global $CONFIG, $METASTRINGS_CACHE;

		// caching doesn't work for case insensitive requests
		if ($case_sensitive) {
			$result = array_search($string, $METASTRINGS_CACHE, true);

			if ($result !== false) {
				return $result;
			}

			// Experimental memcache
			$msfc = null;
			static $metastrings_memcache;
			if ((!$metastrings_memcache) && (is_memcache_available())) {
				$metastrings_memcache = new \ElggMemcache('metastrings_memcache');
			}
			if ($metastrings_memcache) {
				$msfc = $metastrings_memcache->load($string);
			}
			if ($msfc) {
				return $msfc;
			}
		}

		$escaped_string = sanitise_string($string);
		if ($case_sensitive) {
			$query = "SELECT * FROM {$CONFIG->dbprefix}metastrings WHERE string = BINARY '$escaped_string' LIMIT 1";
		} else {
			$query = "SELECT * FROM {$CONFIG->dbprefix}metastrings WHERE string = '$escaped_string'";
		}

		$id = false;
		$results = get_data($query);
		if (is_array($results)) {
			if (!$case_sensitive) {
				$ids = array();
				foreach ($results as $result) {
					$ids[] = $result->id;
				}
				// return immediately because we don't want to cache case insensitive results
				return $ids;
			} else if (isset($results[0])) {
				$id = $results[0]->id;
			}
		}

		if (!$id) {
			$id = _elgg_add_metastring($string);
		}

		$METASTRINGS_CACHE[$id] = $string;

		if ($metastrings_memcache) {
			$metastrings_memcache->save($string, $id);
		}

		return $id;
	}

	/**
	 * Add a metastring.
	 *
	 * @warning You should not call this directly. Use elgg_get_metastring_id().
	 *
	 * @param string $string The value to be normalized
	 * @return int The identifier for this string
	 */
	function add($string) {
		global $CONFIG;

		$escaped_string = sanitise_string($string);

		return insert_data("INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ('$escaped_string')");
	}
}