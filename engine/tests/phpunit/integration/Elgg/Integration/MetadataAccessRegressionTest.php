<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Regression coverage for access enforcement on the metadata read paths.
 *
 * The `metadata` table has no `access_id` column of its own (it was dropped in
 * migration 20200130161435); access is enforced by joining the `entities` table
 * so that its access / enabled / deleted clauses apply to metadata-driven reads.
 *
 * These tests lock that behaviour in for the `(entity_guid, name)` read paths
 * that the composite `entity_guid_name` index targets — `elgg_get_metadata()`
 * and `elgg_get_entities()` with `metadata_name_value_pairs` / `metadata_name`.
 * They are a safety net: an index-aware refactor that served metadata straight
 * off the composite index without the entities access join would leak a private
 * entity's metadata (or the entity itself) to an unauthorized viewer, and these
 * assertions would fail.
 *
 * Each case asserts BOTH directions — the authorized viewer (and IGNORE_ACCESS)
 * still sees the row, the unauthorized viewer sees nothing — so the tests catch
 * over-broad leaks as well as accidental over-filtering.
 */
class MetadataAccessRegressionTest extends IntegrationTestCase {

	protected \ElggUser $owner;

	protected \ElggUser $stranger;

	protected \ElggObject $private;

	protected string $subtype;

	protected string $md_value = 'metadata_access_regression_secret';

	public function up() {
		$this->owner = $this->createUser();
		$this->stranger = $this->createUser();
		$this->subtype = 'metadata_access_regression';

		_elgg_services()->session_manager->setLoggedInUser($this->owner);

		$this->private = $this->createObject([
			'subtype' => $this->subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		// stored as metadata; governed by the entity's access
		$this->private->status = $this->md_value;
	}

	public function down() {
		// created users/objects are auto-cleaned by the Seeding trait
	}

	/**
	 * elgg_get_entities() with a metadata name/value pair must not surface a
	 * private entity to a viewer without access.
	 */
	public function testMetadataNameValuePairDoesNotLeakEntity() {
		$options = [
			'type' => 'object',
			'subtype' => $this->subtype,
			'metadata_name_value_pairs' => [
				'name' => 'status',
				'value' => $this->md_value,
			],
			'limit' => false,
		];

		$this->assertViewerVisibility($options);
	}

	/**
	 * elgg_get_entities() filtering by metadata name only must not surface a
	 * private entity to a viewer without access.
	 */
	public function testMetadataNameOnlyDoesNotLeakEntity() {
		$options = [
			'type' => 'object',
			'subtype' => $this->subtype,
			'metadata_name' => 'status',
			'limit' => false,
		];

		$this->assertViewerVisibility($options);
	}

	/**
	 * elgg_get_metadata() scoped to entity + name must not return a private
	 * entity's metadata to a viewer without access.
	 */
	public function testElggGetMetadataRespectsEntityAccess() {
		$options = [
			'guid' => $this->private->guid,
			'metadata_name' => 'status',
		];

		// unauthorized viewer: nothing
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$md = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_metadata($options);
		});
		$this->assertEmpty($md, 'private entity metadata leaked to an unauthorized viewer');

		// owner: sees the metadata
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$md = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_metadata($options);
		});
		$this->assertNotEmpty($md);
		$this->assertEquals($this->md_value, $md[0]->value);

		// ignore access: sees the metadata regardless of viewer
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$md = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_metadata($options);
		});
		$this->assertNotEmpty($md);
		$this->assertEquals($this->md_value, $md[0]->value);
	}

	/**
	 * Assert the private entity is hidden from the stranger under enforced
	 * access, visible to the owner, and visible under ignored access.
	 */
	protected function assertViewerVisibility(array $options) {
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertCount(0, $found, 'private entity leaked to an unauthorized viewer');

		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->private->guid, $found[0]->guid);

		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertCount(1, $found);
		$this->assertEquals($this->private->guid, $found[0]->guid);
	}
}
