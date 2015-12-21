<?php
namespace Elgg\Database;

use Elgg\Cache\Pool;
use Elgg\Database;


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

	/** @var Pool */
	private $cache;

	/** @var Database */
	private $db;

	/**
	 * Constructor
	 * 
	 * @param Pool     $cache A cache for this table.
	 * @param Database $db    The database.
	 */
	public function __construct(Pool $cache, Database $db) {
		$this->cache = $cache;
		$this->db = $db;
	}

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
	 *
	 * @see elgg_get_metastring_id
	 */
	function getId($string, $case_sensitive = true) {
		if ($case_sensitive) {
			return $this->getIdCaseSensitive($string);
		} else {
			return $this->getIdCaseInsensitive($string);
		}
	}

	/**
	 * Get a map of strings to their metastring identifiers (case sensitive matches)
	 *
	 * @param string[] $string_keys Strings to look up
	 *
	 * @return int[] map of [string] => [id]
	 *
	 * @see elgg_get_metastring_map
	 */
	function getMap(array $string_keys) {
		if (!$string_keys) {
			return [];
		}
		if (count($string_keys) === 1) {
			$key = reset($string_keys);
			return [$key => $this->getIdCaseSensitive($key)];
		}

		$missing = array_fill_keys($string_keys, true);

		$set_element = array_map(function ($string) {
			return "BINARY '" . $this->db->sanitizeString($string) . "'";
		}, $string_keys);
		$set = implode(',', $set_element);

		$query = "SELECT * FROM {$this->getTableName()} WHERE string IN ($set)";
		$ret = [];

		foreach ($this->db->getData($query) as $row) {
			$ret[$row->string] = (int)$row->id;
			unset($missing[$row->string]);
		}
		foreach (array_keys($missing) as $string) {
			$ret[$string] = $this->getIdCaseSensitive($string);
		}

		return $ret;
	}
	
	/**
	 * Gets the id associated with this string, case-sensitively.
	 * Will add the string to the table if not present.
	 * 
	 * @param string $string The value
	 * 
	 * @return int
	 */
	private function getIdCaseSensitive($string) {
		$string = (string)$string;
		return $this->cache->get($string, function() use ($string) {
			$escaped_string = $this->db->sanitizeString($string);
			$query = "SELECT id FROM {$this->getTableName()} WHERE string = BINARY '$escaped_string' LIMIT 1";
			$results = $this->db->getData($query);
			if (isset($results[0])) {
				return $results[0]->id;
			} else {
				return $this->add($string);
			}
		});
	}
	
	/**
	 * Gets all ids associated with this string when taken case-insensitively.
	 * Will add the string to the table if not present.
	 * 
	 * @param string $string The value
	 * 
	 * @return int[]
	 */
	private function getIdCaseInsensitive($string) {
		$string = (string)$string;
		// caching doesn't work for case insensitive requests
		$escaped_string = $this->db->sanitizeString($string);
		$query = "SELECT id FROM {$this->getTableName()} WHERE string = '$escaped_string'";
		$results = $this->db->getData($query);
		$ids = array();
		foreach ($results as $result) {
			$ids[] = $result->id;
		}
		if (empty($ids)) {
			$ids[] = $this->add($string);
		}
		return $ids;
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
		$escaped_string = $this->db->sanitizeString(trim($string));
	
		return $this->db->insertData("INSERT INTO {$this->getTableName()} (string) VALUES ('$escaped_string')");
	}
	
	/**
	 * The full name of the metastrings table, including prefix.
	 * 
	 * @return string
	 */
	public function getTableName() {
		return $this->db->getTablePrefix() . "metastrings";
	}
}