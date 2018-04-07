<?php

namespace ElggPlugin\Profile;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

/**
 * Copy all profile field metadata to annotations, with each name prefixed with "profile:"
 */
class AnnotationMigration implements Batch {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2017040700;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {
		// @todo We need somehow restrict this upgrade to users created before upgrade 3.x was performed

		// Because we are not really checking against real entities and metadata/annotation values
		// this upgrade gets regenerated whenever upgrade locator runs
		// so let's make sure it hasn't completed before
		$upgrades = elgg_get_entities([
			'types' => 'object',
			'subtypes' => 'elgg_upgrade',
			'private_setting_name_value_pairs' => [
				'id' => "profile:{$this->getVersion()}",
				'is_completed' => true,
			],
			'count' => true,
		]);

		return $upgrades;
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return count($this->getFieldNames());
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {
		$name = $this->getFieldNames()[$offset];

		$db = elgg()->db;
		$sql = "
			INSERT INTO {$db->prefix}annotations
				  (entity_guid, `name`,    `value`, value_type, owner_guid, access_id, time_created, enabled)
			SELECT entity_guid, :new_name, `value`, value_type, owner_guid, access_id, time_created, enabled
			FROM {$db->prefix}metadata
			WHERE `name` = :old_name
			AND entity_guid IN (
				SELECT guid FROM {$db->prefix}entities WHERE type = 'user'
			)
		";

		try {
			$db->updateData($sql, false, [
				':old_name' => $name,
				':new_name' => "profile:$name",
			]);
			$result->addSuccesses(1);
		} catch (\DatabaseException $e) {
			$result->addError("Profile field '$name' could not be migrated: " . $e->getMessage());
			$result->addFailures(1);
		}

		return $result;
	}

	/**
	 * Get the profile field names
	 *
	 * @return string[]
	 */
	private function getFieldNames() {
		return array_keys(elgg_get_config('profile_fields'));
	}
}
