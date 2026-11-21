<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Regression coverage for disabled / soft-deleted entity exclusion on the
 * metadata read paths.
 *
 * The `metadata` table carries no `enabled` or `deleted` column of its own;
 * visibility of a metadata row is enforced by INNER JOINing the `entities`
 * table (see {@see \Elgg\Database\Metadata::buildEntityWhereClause()}) so that
 * the entity's `enabled = 'yes'` / `deleted = 'no'` clauses
 * ({@see \Elgg\Database\Clauses\AccessWhereClause}) apply to every
 * metadata-driven read.
 *
 * These tests lock that behaviour in for the `(entity_guid, name)` read paths
 * that the upcoming composite `entity_guid_name` index targets —
 * `elgg_get_metadata()` and `elgg_get_entities()` with
 * `metadata_name_value_pairs` / `metadata_name`. An index-aware refactor that
 * served metadata straight off the composite index without the entities join
 * would surface a DISABLED or SOFT-DELETED entity (or its metadata) that the
 * current query correctly hides, and these assertions would fail.
 *
 * There is NO per-query option to include disabled / deleted rows: inclusion is
 * governed by the `elgg_call()` flags `ELGG_SHOW_DISABLED_ENTITIES` and
 * `ELGG_SHOW_DELETED_ENTITIES` (see engine/lib/constants.php:123-127 and
 * {@see \Elgg\Database\Clauses\AccessWhereClause::prepare()} lines 46-52, which
 * derive `use_enabled_clause` / `use_deleted_clause` from the session
 * visibility toggled by those flags). All reads below run under
 * `ELGG_IGNORE_ACCESS` so access is never the reason a row is hidden — this
 * isolates the enabled / deleted clauses (access itself is covered by the
 * sibling {@see MetadataAccessRegressionTest}). Each case asserts BOTH
 * directions: a normal enabled entity is always found, and the disabled /
 * deleted entity is hidden by default but surfaces once the query opts in.
 */
class MetadataDisabledDeletedRegressionTest extends IntegrationTestCase {

	protected \ElggUser $owner;

	protected string $subtype;

	protected bool $trash_enabled;

	protected \ElggObject $enabled;

	protected \ElggObject $disabled;

	protected \ElggObject $deleted;

	// distinctive names/values to avoid collisions with other rows in the shared DB
	protected string $enabled_name = 'mddd_enabled_name';

	protected string $enabled_value = 'mddd_enabled_value';

	protected string $disabled_name = 'mddd_disabled_name';

	protected string $disabled_value = 'mddd_disabled_value';

	protected string $deleted_name = 'mddd_deleted_name';

	protected string $deleted_value = 'mddd_deleted_value';

	public function up() {
		$this->subtype = 'metadata_disabled_deleted_regression';
		$this->trash_enabled = _elgg_services()->config->trash_enabled;

		$this->owner = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->owner);

		// soft-delete requires trash + the 'restorable' capability, otherwise
		// delete() falls through to a persistent delete (see ElggEntity::delete())
		_elgg_services()->config->trash_enabled = true;
		elgg_entity_enable_capability('object', $this->subtype, 'restorable');

		// a normal, enabled entity: the positive control
		$this->enabled = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->enabled->{$this->enabled_name} = $this->enabled_value;

		// an entity that will be disabled (entities.enabled = 'no')
		$this->disabled = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->disabled->{$this->disabled_name} = $this->disabled_value;
		$this->disabled->disable();

		// an entity that will be soft-deleted / trashed (entities.deleted = 'yes')
		$this->deleted = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
		$this->deleted->{$this->deleted_name} = $this->deleted_value;
		$this->deleted->delete();
	}

	public function down() {
		_elgg_services()->config->trash_enabled = $this->trash_enabled;
		elgg_entity_disable_capability('object', $this->subtype, 'restorable');

		// created entities are auto-cleaned by the TestSeeding trait
	}

	/**
	 * elgg_get_entities() with a metadata name/value pair must exclude a
	 * DISABLED entity by default and surface it only under
	 * ELGG_SHOW_DISABLED_ENTITIES; a normal enabled entity is always found.
	 */
	public function testMetadataNameValuePairExcludesDisabledEntity() {
		// positive control: enabled entity is found by default
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () {
			return elgg_get_entities([
				'type' => 'object',
				'subtype' => $this->subtype,
				'metadata_name_value_pairs' => [
					'name' => $this->enabled_name,
					'value' => $this->enabled_value,
				],
				'limit' => false,
			]);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->enabled->guid, $found[0]->guid);

		$disabled_options = [
			'type' => 'object',
			'subtype' => $this->subtype,
			'metadata_name_value_pairs' => [
				'name' => $this->disabled_name,
				'value' => $this->disabled_value,
			],
			'limit' => false,
		];

		// default: the disabled entity is hidden
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($disabled_options) {
			return elgg_get_entities($disabled_options);
		});
		$this->assertCount(0, $found, 'disabled entity surfaced via metadata name/value pair by default');

		// opt in via ELGG_SHOW_DISABLED_ENTITIES: the disabled entity is found
		$found = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($disabled_options) {
			return elgg_get_entities($disabled_options);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->disabled->guid, $found[0]->guid);
	}

	/**
	 * elgg_get_entities() filtering by metadata name only must exclude a
	 * SOFT-DELETED entity by default and surface it only under
	 * ELGG_SHOW_DELETED_ENTITIES; a normal enabled entity is always found.
	 */
	public function testMetadataNameOnlyExcludesDeletedEntity() {
		// sanity: the entity really was trashed, not persistently deleted
		$this->assertTrue($this->deleted->isDeleted());

		// positive control: enabled entity is found by default
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () {
			return elgg_get_entities([
				'type' => 'object',
				'subtype' => $this->subtype,
				'metadata_name' => $this->enabled_name,
				'limit' => false,
			]);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->enabled->guid, $found[0]->guid);

		$deleted_options = [
			'type' => 'object',
			'subtype' => $this->subtype,
			'metadata_name' => $this->deleted_name,
			'limit' => false,
		];

		// default: the soft-deleted entity is hidden
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($deleted_options) {
			return elgg_get_entities($deleted_options);
		});
		$this->assertCount(0, $found, 'soft-deleted entity surfaced via metadata name by default');

		// opt in via ELGG_SHOW_DELETED_ENTITIES: the soft-deleted entity is found
		$found = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DELETED_ENTITIES, function () use ($deleted_options) {
			return elgg_get_entities($deleted_options);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->deleted->guid, $found[0]->guid);
	}

	/**
	 * elgg_get_metadata() scoped to entity + name must return nothing for a
	 * DISABLED entity by default, surface it only under
	 * ELGG_SHOW_DISABLED_ENTITIES, and always return an enabled entity's
	 * metadata.
	 */
	public function testElggGetMetadataExcludesDisabledEntity() {
		// positive control: enabled entity's metadata is returned by default
		$md = elgg_call(ELGG_IGNORE_ACCESS, function () {
			return elgg_get_metadata([
				'guid' => $this->enabled->guid,
				'metadata_name' => $this->enabled_name,
			]);
		});
		$this->assertNotEmpty($md);
		$this->assertEquals($this->enabled_value, $md[0]->value);

		$disabled_options = [
			'guid' => $this->disabled->guid,
			'metadata_name' => $this->disabled_name,
		];

		// default: no metadata is returned for the disabled entity
		$md = elgg_call(ELGG_IGNORE_ACCESS, function () use ($disabled_options) {
			return elgg_get_metadata($disabled_options);
		});
		$this->assertEmpty($md, 'disabled entity metadata returned by elgg_get_metadata() by default');

		// opt in via ELGG_SHOW_DISABLED_ENTITIES: the metadata is returned
		$md = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($disabled_options) {
			return elgg_get_metadata($disabled_options);
		});
		$this->assertNotEmpty($md);
		$this->assertEquals($this->disabled_value, $md[0]->value);
	}
}
