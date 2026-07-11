<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Regression coverage for elgg_count_entities() over metadata filters.
 *
 * The count query joins the entities table to the metadata table on entity_guid
 * and filters by metadata name (+ CAST value) — the same (entity_guid, name) read
 * surface the composite index targets. These tests pin the COUNT results across
 * selective and non-selective values, multi-pair AND/OR, metadata_name-only, and
 * access/enabled enforcement, so any query-builder or index change made to tune
 * the count plan cannot change the numbers it returns.
 *
 * Behavior is independent of the physical plan, so these pass with or without the
 * index and before/after any query tuning.
 */
class MetadataCountRegressionTest extends IntegrationTestCase {

	protected \ElggUser $owner;

	protected \ElggUser $stranger;

	protected string $subtype = 'metadata_count_regression';

	public function up() {
		$this->owner = $this->createUser();
		$this->stranger = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
	}

	public function down() {
		// created users/objects are auto-cleaned by the Seeding trait
	}

	protected function make(int $access_id, array $metadata): \ElggObject {
		$object = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => $access_id,
		]);
		foreach ($metadata as $name => $value) {
			$object->$name = $value;
		}

		return $object;
	}

	protected function countBy(array $options): int {
		return (int) elgg_count_entities([
			'type' => 'object',
			'subtype' => $this->subtype,
		] + $options);
	}

	/**
	 * A non-selective value that every entity carries: the count equals the
	 * number of accessible entities.
	 */
	public function testCountNonSelectiveValue() {
		// 4 public + 1 private, all carrying status=live
		for ($i = 0; $i < 4; $i++) {
			$this->make(ACCESS_PUBLIC, ['status' => 'live']);
		}
		$this->make(ACCESS_PRIVATE, ['status' => 'live']);

		$options = ['metadata_name_value_pairs' => ['name' => 'status', 'value' => 'live']];

		// stranger sees only the 4 public
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$this->assertSame(4, elgg_call(ELGG_ENFORCE_ACCESS, fn() => $this->countBy($options)));

		// owner sees all 5
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$this->assertSame(5, elgg_call(ELGG_ENFORCE_ACCESS, fn() => $this->countBy($options)));

		// ignore access sees all 5
		$this->assertSame(5, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy($options)));
	}

	/**
	 * A selective value carried by only some entities: the count reflects the
	 * value filter, not just the metadata name.
	 */
	public function testCountSelectiveValue() {
		$this->make(ACCESS_PUBLIC, ['status' => 'live']);
		$this->make(ACCESS_PUBLIC, ['status' => 'live']);
		$this->make(ACCESS_PUBLIC, ['status' => 'draft']);
		$this->make(ACCESS_PUBLIC, ['status' => 'archived']);

		$this->assertSame(2, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy([
			'metadata_name_value_pairs' => ['name' => 'status', 'value' => 'live'],
		])));
		$this->assertSame(1, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy([
			'metadata_name_value_pairs' => ['name' => 'status', 'value' => 'draft'],
		])));
		// name only, ignoring value: all 4 carry status
		$this->assertSame(4, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy([
			'metadata_names' => ['status'],
		])));
	}

	/**
	 * Two pairs: AND counts entities carrying both; OR counts entities carrying
	 * either.
	 */
	public function testCountMultiPairAndOr() {
		$this->make(ACCESS_PUBLIC, ['color' => 'blue', 'size' => 'large']); // both
		$this->make(ACCESS_PUBLIC, ['color' => 'blue', 'size' => 'small']); // only color
		$this->make(ACCESS_PUBLIC, ['color' => 'red', 'size' => 'large']);  // only size

		$pairs = [
			['name' => 'color', 'value' => 'blue'],
			['name' => 'size', 'value' => 'large'],
		];

		$this->assertSame(1, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy([
			'metadata_name_value_pairs' => $pairs,
		])));
		$this->assertSame(3, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy([
			'metadata_name_value_pairs' => $pairs,
			'metadata_name_value_pairs_operator' => 'OR',
		])));
	}

	/**
	 * The count excludes disabled entities by default (enabled clause rides on the
	 * entities join), and includes them under ELGG_SHOW_DISABLED_ENTITIES.
	 */
	public function testCountExcludesDisabled() {
		$this->make(ACCESS_PUBLIC, ['status' => 'live']);
		$disabled = $this->make(ACCESS_PUBLIC, ['status' => 'live']);
		$disabled->disable();

		$options = ['metadata_name_value_pairs' => ['name' => 'status', 'value' => 'live']];

		$this->assertSame(1, elgg_call(ELGG_IGNORE_ACCESS, fn() => $this->countBy($options)));
		$this->assertSame(2, elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, fn() => $this->countBy($options)));
	}
}
