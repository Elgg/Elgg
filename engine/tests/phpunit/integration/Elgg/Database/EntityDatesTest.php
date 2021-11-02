<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group EntityDates
 */
class EntityDatesTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->session->setIgnoreAccess(true);
		
		$this->seed();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		_elgg_services()->session->setIgnoreAccess(false);
	}

	/**
	 * Create testing entities
	 *
	 * @return void
	 */
	protected function seed() {
		
		$users = [
			$this->createUser(),
			$this->createUser(),
		];
		
		// multiple owners ensure that more than 1 entity exists on the given time
		foreach ($users as $owner) {
			$this->createObject([
				'subtype' => 'entity_dates_a',
				'time_created' => strtotime('15 May 2018'),
				'owner_guid' => $owner->guid,
				'container_guid' => $owner->guid,
			]);
			$this->createObject([
				'subtype' => 'entity_dates_a',
				'time_created' => strtotime('15 June 2018'),
				'owner_guid' => $owner->guid,
				'container_guid' => $owner->guid,
			]);
			$this->createObject([
				'subtype' => 'entity_dates_b',
				'time_created' => strtotime('15 July 2018'),
				'owner_guid' => $owner->guid,
				'container_guid' => $owner->guid,
			]);
			$this->createObject([
				'subtype' => 'entity_dates_b',
				'time_created' => strtotime('15 August 2018'),
				'owner_guid' => $owner->guid,
				'container_guid' => $owner->guid,
			]);
		}
	}
	
	public function testGetEntityDates() {
		$dates = elgg_get_entity_dates([
			'type' => 'object',
			'subtypes' => [
				'entity_dates_a',
				'entity_dates_b',
			],
		]);
		
		$this->assertNotEmpty($dates);
		
		$expected = [
			'201805',
			'201806',
			'201807',
			'201808',
		];
		
		$this->assertEquals($expected, $dates);
	}
	
	public function testGetEntityDatesForSubtype() {
		$dates = elgg_get_entity_dates([
			'type' => 'object',
			'subtypes' => [
				'entity_dates_a',
			],
		]);
		
		$this->assertNotEmpty($dates);
		
		$expected = [
			'201805',
			'201806',
		];
		
		$this->assertEquals($expected, $dates);
		
		$dates = elgg_get_entity_dates([
			'type' => 'object',
			'subtypes' => [
				'entity_dates_b',
			],
		]);
		
		$this->assertNotEmpty($dates);
		
		$expected = [
			'201807',
			'201808',
		];
		
		$this->assertEquals($expected, $dates);
	}
	
	public function testGetEntityDatesWithTimeContraint() {
		$dates = elgg_get_entity_dates([
			'type' => 'object',
			'subtypes' => [
				'entity_dates_a',
				'entity_dates_b',
			],
			'created_before' => '1 May 2018',
		]);
		
		$this->assertEmpty($dates);
	}
}
