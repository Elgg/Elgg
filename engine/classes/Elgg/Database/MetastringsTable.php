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
	private $id_cache;

	/** @var Pool */
	private $string_cache;

	/** @var Database */
	private $db;

	/**
	 * Constructor
	 * 
	 * @param Pool     $id_cache     Cache of metastring IDs (by strings)
	 * @param Pool     $string_cache Cache of strings (by metastring IDs)
	 * @param Database $db           The database.
	 */
	public function __construct(Pool $id_cache, Pool $string_cache, Database $db) {
		$this->id_cache = $id_cache;
		$this->string_cache = $string_cache;
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
	public function getId($string, $case_sensitive = true) {
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
	public function getIds(array $string_keys) {
		if (!$string_keys) {
			return [];
		}
		if (count($string_keys) === 1) {
			$key = reset($string_keys);
			return [$key => $this->getIdCaseSensitive($key)];
		}

		$missing = array_fill_keys($string_keys, true);

		foreach ($string_keys as $string_key) {
			$set_element[] = "BINARY ?";
			$params[] = (string)$string_key;
		}

		$set = implode(',', $set_element);

		$query = "
			SELECT *
			FROM {$this->getTableName()}
			WHERE string IN ($set)
		";
		$ret = [];

		foreach ($this->db->getData($query, null, $params) as $row) {
			$ret[$row->string] = (int)$row->id;
			unset($missing[$row->string]);
		}
		foreach (array_keys($missing) as $string) {
			$ret[$string] = $this->getIdCaseSensitive($string);
		}

		return $ret;
	}

	/**
	 * Fetch strings for given IDs.
	 *
	 * @param int[] $ids Metastring IDs
	 *
	 * @return string[] map of [id] => [string]. Missing strings will not be present
	 */
	public function getStrings(array $ids) {
		$ret = [];
		$ids = array_map('intval', $ids);

		// try cache
		foreach ($ids as $i => $id) {
			$string = $this->string_cache->get($id);
			if ($string !== null) {
				$ret[$id] = $string;
				unset($ids[$i]);
			}
		}

		if (!$ids) {
			return $ret;
		}

		$query = "
			SELECT *
			FROM {$this->getTableName()}
			WHERE id IN (" . implode(',', $ids) . ")
		";

		foreach ($this->db->getData($query) as $row) {
			$ret[$row->id] = $row->string;

			// cache short strings (that may be identifiers/common values). Caching everything makes it
			// too easy to eat memory.
			if (strlen($row->string) < 25) {
				$this->string_cache->put($row->id, $row->string);
			}
		}

		return $ret;
	}

	/**
	 * Populate the "name" and "value" properties of metadata rows based on "name_id" and "value_id"
	 *
	 * @param \stdClass[] $rows Rows from the Metadata table
	 *
	 * @return void
	 */
	public function populateMetadataRows($rows) {
		$ids = [];
		foreach ($rows as $row) {
			$ids[$row->name_id] = true;
			$ids[$row->value_id] = true;
		}
		$strings = $this->getStrings(array_keys($ids));
		foreach ($rows as $row) {
			$row->name = isset($strings[$row->name_id]) ? $strings[$row->name_id] : null;
			$row->value = isset($strings[$row->value_id]) ? $strings[$row->value_id] : null;
		}
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
		return $this->id_cache->get($string, function() use ($string) {
			$query = "SELECT id FROM {$this->getTableName()} WHERE string = BINARY ? LIMIT 1";
			$results = $this->db->getData($query, null, [$string]);
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
		$query = "SELECT id FROM {$this->getTableName()} WHERE string = ?";
		$results = $this->db->getData($query, null, [$string]);
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
	public function add($string) {
		return $this->db->insertData(
			"INSERT INTO {$this->getTableName()} (string) VALUES (?)",
			[trim($string)]
		);
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