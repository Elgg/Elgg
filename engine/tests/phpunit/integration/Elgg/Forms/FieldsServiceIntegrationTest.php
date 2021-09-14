<?php

namespace Elgg\Forms;

use Elgg\IntegrationTestCase;

class FieldsServiceIntegrationTest extends IntegrationTestCase {

	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->hooks->backup();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testFieldsConfig() {
		// adding to English as checks for language keys defaults to English
		// this prevents this test from failing when the default language isn't English
		add_translation('en', ['fields:foo:bar:field_1' => 'Label 1']);
		
		_elgg_services()->hooks->registerHandler('fields', 'foo:bar', function() {
			return [
				[
					'name' => null,
					'#type' => 'missing name',
				],
				[
					'name' => 'missing type',
					'#type' => null,
				],
				[
					'name' => 'field_1',
					'#type' => 'text',
				],
				[
					'name' => 'field_2',
					'#type' => 'text',
					'#label' => 'label',
				],
				[
					'name' => 'field_3',
					'#type' => 'text',
					'something' => 'else',
				],
				
			];
		});
		
		_elgg_services()->logger->disable();
		
		$this->assertEquals([
			[
				'#type' => 'text',
				'name' => 'field_1',
				'#label' => 'Label 1',
			],
			[
				'#type' => 'text',
				'name' => 'field_2',
				'#label' => 'label',
			],
			[
				'#type' => 'text',
				'name' => 'field_3',
				'something' => 'else',
			],
		], elgg()->fields->get('foo', 'bar'));
		
		_elgg_services()->logger->enable();
	}
	
	public function testFieldsConfigIsCached() {
		$fields = [
			[
				'name' => 'field_1',
				'#type' => 'text',
			],
		];
		
		$hook = $this->registerTestingHook('fields', 'foo:bar2', function() use ($fields) {
			return $fields;
		});
		
		$hook->assertNumberOfCalls(0);
		
		$this->assertEquals($fields, elgg()->fields->get('foo', 'bar2'));
		$hook->assertNumberOfCalls(1);
		
		$this->assertEquals($fields, elgg()->fields->get('foo', 'bar2'));
		$this->assertEquals($fields, elgg()->fields->get('foo', 'bar2'));
		$hook->assertNumberOfCalls(1);
	}
}
