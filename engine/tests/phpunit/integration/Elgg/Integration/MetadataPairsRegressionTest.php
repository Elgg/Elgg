<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Regression coverage for multi-pair `metadata_name_value_pairs` correctness
 * (AND / OR merge operators) combined with entity access enforcement.
 *
 * These read paths sit directly on top of the `(entity_guid, name)` metadata
 * lookup that the upcoming composite `entity_guid_name` index targets, so the
 * behaviour is locked in first: the index must not change which entities a
 * multi-pair filter returns, nor whom it returns them to.
 *
 * The AND / OR merge semantics under test are implemented in
 * Elgg\Database\Entities::buildPairedMetadataClause() (Entities.php:263-279):
 *
 *  - OR (or a single pair): one shared metadata join with a null name filter,
 *    parts merged with OR — an entity matching EITHER pair qualifies
 *    (Entities.php:268-269).
 *  - AND (default) with multiple pairs: a SEPARATE metadata join per pair,
 *    keyed on that pair's own name, parts merged with AND — an entity must
 *    carry BOTH distinct metadata rows to qualify (Entities.php:270-272).
 *
 * Access is enforced independently, by the entities-table join and its access
 * clause, so every case asserts BOTH directions: a private entity that matches
 * the metadata filter stays hidden from an unauthorized viewer but visible to
 * the owner and under ELGG_IGNORE_ACCESS.
 */
class MetadataPairsRegressionTest extends IntegrationTestCase {

	protected \ElggUser $owner;

	protected \ElggUser $stranger;

	protected string $subtype = 'metadata_pairs_regression';

	// distinctive names/values so result counts are exact against a shared DB
	protected string $name_a = 'mpr_color';

	protected string $value_a = 'mpr_blue_9f3c1';

	protected string $name_b = 'mpr_size';

	protected string $value_b = 'mpr_large_7a2e0';

	/**
	 * public entity carrying BOTH pairs
	 */
	protected \ElggObject $both;

	/**
	 * public entity carrying ONLY the first pair
	 */
	protected \ElggObject $only_a;

	/**
	 * public entity carrying ONLY the second pair
	 */
	protected \ElggObject $only_b;

	/**
	 * private entity carrying BOTH pairs, owned by $owner
	 */
	protected \ElggObject $private_both;

	public function up() {
		$this->owner = $this->createUser();
		$this->stranger = $this->createUser();

		_elgg_services()->session_manager->setLoggedInUser($this->owner);

		$this->both = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->both->{$this->name_a} = $this->value_a;
		$this->both->{$this->name_b} = $this->value_b;

		$this->only_a = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->only_a->{$this->name_a} = $this->value_a;

		$this->only_b = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->only_b->{$this->name_b} = $this->value_b;

		$this->private_both = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PRIVATE,
		]);
		$this->private_both->{$this->name_a} = $this->value_a;
		$this->private_both->{$this->name_b} = $this->value_b;
	}

	public function down() {
		// created users/objects are auto-cleaned by the Seeding trait
	}

	/**
	 * The two metadata pairs merged with AND (default operator).
	 *
	 * @return array
	 */
	protected function andPairs(): array {
		return [
			'type' => 'object',
			'subtype' => $this->subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $this->name_a,
					'value' => $this->value_a,
				],
				[
					'name' => $this->name_b,
					'value' => $this->value_b,
				],
			],
			'limit' => false,
		];
	}

	/**
	 * The two metadata pairs merged with OR.
	 *
	 * @return array
	 */
	protected function orPairs(): array {
		return $this->andPairs() + [
			'metadata_name_value_pairs_operator' => 'OR',
		];
	}

	/**
	 * Extract a sorted list of guids from a set of entities.
	 *
	 * @param \ElggEntity[] $entities entities
	 *
	 * @return int[]
	 */
	protected function guids(array $entities): array {
		$guids = array_map(function (\ElggEntity $e) {
			return (int) $e->guid;
		}, $entities);
		sort($guids);

		return $guids;
	}

	/**
	 * AND: only an entity carrying BOTH pairs is returned; an entity carrying
	 * only one of the two pairs is excluded.
	 */
	public function testTwoPairsCombinedWithAndRequiresBothPairs() {
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () {
			return elgg_get_entities($this->andPairs());
		});

		$expected = [$this->both->guid, $this->private_both->guid];
		sort($expected);

		$this->assertEquals($expected, $this->guids($found));

		$guids = $this->guids($found);
		$this->assertNotContains((int) $this->only_a->guid, $guids, 'AND matched an entity carrying only the first pair');
		$this->assertNotContains((int) $this->only_b->guid, $guids, 'AND matched an entity carrying only the second pair');
	}

	/**
	 * OR: every entity carrying EITHER pair is returned.
	 */
	public function testTwoPairsCombinedWithOrReturnsEitherMatch() {
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () {
			return elgg_get_entities($this->orPairs());
		});

		$expected = [
			$this->both->guid,
			$this->only_a->guid,
			$this->only_b->guid,
			$this->private_both->guid,
		];
		sort($expected);

		$this->assertEquals($expected, $this->guids($found));
	}

	/**
	 * A multi-pair (AND) filter must respect entity access: the private entity
	 * matching both pairs is hidden from an unauthorized viewer, but visible to
	 * the owner and under ELGG_IGNORE_ACCESS.
	 */
	public function testMultiPairAndRespectsEntityAccess() {
		$options = $this->andPairs();

		// unauthorized viewer: only the public match, private is not leaked
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals([(int) $this->both->guid], $this->guids($found), 'private multi-pair match leaked to an unauthorized viewer');

		// owner: sees both the public and the private match
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$expected = [$this->both->guid, $this->private_both->guid];
		sort($expected);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($expected, $this->guids($found));

		// ignore access: private match visible regardless of viewer
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($expected, $this->guids($found));
	}

	/**
	 * A count query over a multi-pair (AND) filter returns the correct integer
	 * and respects access the same way the fetch does.
	 */
	public function testMultiPairAndCountRespectsAccess() {
		$options = $this->andPairs() + ['count' => true];

		// unauthorized viewer: counts only the public match
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$count = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertSame(1, $count, 'count leaked the private multi-pair match to an unauthorized viewer');

		// owner: counts the public and the private match
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$count = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertSame(2, $count);

		// ignore access: counts both regardless of viewer
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$count = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertSame(2, $count);
	}
}
