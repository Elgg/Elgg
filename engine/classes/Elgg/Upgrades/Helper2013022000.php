<?php
namespace Elgg\Upgrades;

/**
 * Helper for data directory upgrade
 *
 * @access private
 */
class Helper2013022000 {
	const RELATIONSHIP_SUCCESS = '2013022000';
	const RELATIONSHIP_FAILURE = '2013022000_fail';

	/**
	 * @var int Site GUID
	 */
	protected $siteGuid;

	/**
	 * @var string DB table prefix
	 */
	protected $dbPrefix;

	/**
	 * @param int    $siteGuid Site GUID
	 * @param string $dbPrefix DB table prefix
	 */
	public function __construct($siteGuid, $dbPrefix) {
		$this->siteGuid = $siteGuid;
		$this->dbPrefix = $dbPrefix;
	}

	/**
	 * Get elgg_get_entities() options for fetching users who need data migration
	 *
	 * @return array
	 */
	public function getBatchOptions() {
		$relationship1 = sanitise_string(self::RELATIONSHIP_SUCCESS);
		$relationship2 = sanitise_string(self::RELATIONSHIP_FAILURE);
		// find users without either relationship
		return array(
			'type' => 'user',
			'callback' => '',
			'order_by' => 'e.guid',
			'joins' => array(
				"LEFT JOIN {$this->dbPrefix}entity_relationships er1
				ON (e.guid = er1.guid_one
					AND er1.guid_two = {$this->siteGuid}
					AND er1.relationship = '$relationship1')
				",
				"LEFT JOIN {$this->dbPrefix}entity_relationships er2
				ON (e.guid = er2.guid_one
					AND er2.guid_two = {$this->siteGuid}
					AND er2.relationship = '$relationship2')
				",
			),
			'wheres' => array("er1.guid_one IS NULL AND er2.guid_one IS NULL"),
			'limit' => false,
		);
	}

	/**
	 * Get number of users who need data migration
	 *
	 * @return int
	 */
	public function countUnmigratedUsers() {
		$opts = $this->getBatchOptions();
		$opts['count'] = true;
		return elgg_get_entities($opts);
	}

	/**
	 * Get the old directory location
	 *
	 * @param \stdClass $user_row
	 * @return string
	 */
	public function makeMatrix($user_row) {
		$time_created = date('Y/m/d', $user_row->time_created);
		return "$time_created/$user_row->guid/";
	}

	/**
	 * Remove directory if all users moved out of it
	 *
	 * @param string $dir
	 * @return bool
	 */
	public function removeDirIfEmpty($dir) {
		$files = scandir($dir);

		foreach ($files as $file) {
			if ($file == '..' || $file == '.') {
				continue;
			}

			// not empty.
			if (is_file("$dir/$file")) {
				return false;
			}

			// subdir not empty
			if (is_dir("$dir/$file") && !$this->removeDirIfEmpty("$dir/$file")) {
				return false;
			}
		}

		// only contains empty subdirs
		return rmdir($dir);
	}

	/**
	 * Get the base directory name as int
	 *
	 * @param int $guid GUID of the user
	 * @return int
	 */
	public function getLowerBucketBound($guid) {
		$bucket_size = \Elgg\EntityDirLocator::BUCKET_SIZE;
		if ($guid < 1) {
			return false;
		}
		return (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
	}

	/**
	 * Mark the user as a successful data migration
	 *
	 * @param int $guid
	 */
	public function markSuccess($guid) {
		add_entity_relationship($guid, self::RELATIONSHIP_SUCCESS, $this->siteGuid);
	}

	/**
	 * Mark the user as having failed data migration
	 *
	 * @param int $guid
	 */
	public function markFailure($guid) {
		add_entity_relationship($guid, self::RELATIONSHIP_FAILURE, $this->siteGuid);
	}

	/**
	 * Remove the records for failed migrations
	 */
	public function forgetFailures() {
		$relationship = sanitise_string(self::RELATIONSHIP_FAILURE);
		_elgg_services()->db->updateData("
			DELETE FROM {$this->dbPrefix}entity_relationships
			WHERE relationship = '$relationship'
			  AND guid_two = {$this->siteGuid}
		");
	}

	/**
	 * Remove the records for successful migrations
	 */
	public function forgetSuccesses() {
		$relationship = sanitise_string(self::RELATIONSHIP_SUCCESS);
		_elgg_services()->db->updateData("
			DELETE FROM {$this->dbPrefix}entity_relationships
			WHERE relationship = '$relationship'
			  AND guid_two = {$this->siteGuid}
		");
	}

	/**
	 * Are there any failures on record?
	 *
	 * @return bool
	 */
	public function hasFailures() {
		$relationship = sanitise_string(self::RELATIONSHIP_FAILURE);
		$sql = "
			SELECT COUNT(*) AS cnt FROM {$this->dbPrefix}entity_relationships
			WHERE relationship = '$relationship'
			  AND guid_two = {$this->siteGuid}
		";
		$row = _elgg_services()->db->getDataRow($sql);
		return ($row->cnt > 0);
	}
}

