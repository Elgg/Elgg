<?php

namespace Elgg\Forms;

use Elgg\IntegrationTestCase;

class FieldsServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->events->backup();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		_elgg_services()->events->restore();
	}

	public function testFieldsConfig() {
		// adding to English as checks for language keys defaults to English
		// this prevents this test from failing when the default language isn't English
		_elgg_services()->translator->addTranslation('en', ['fields:foo:bar:field_1' => 'Label 1']);
		
		_elgg_services()->events->registerHandler('fields', 'foo:bar', function() {
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
	}
	
	public function testFieldsConfigIsCached() {
		$fields = [
			[
				'name' => 'field_1',
				'#type' => 'text',
			],
		];

		$event = $this->registerTestingEvent('fields', 'object:foo_bar2', function() use ($fields) {
			return $fields;
		});

		$event->assertNumberOfCalls(0);

		$this->assertEquals($fields, elgg()->fields->get('object', 'foo_bar2'));
		$event->assertNumberOfCalls(1);

		$this->assertEquals($fields, elgg()->fields->get('object', 'foo_bar2'));
		$this->assertEquals($fields, elgg()->fields->get('object', 'foo_bar2'));
		$event->assertNumberOfCalls(1);
	}

	public function testFieldPriority()	{
		$fields = [
			[
				'name' => 'field_1',
				'#type' => 'text',
				'priority' => 500,
			],
			[
				'name' => 'field_2',
				'#type' => 'text',
			],
			[
				'name' => 'field_3',
				'#type' => 'text',
				'priority' => 10,
			],
		];

		$expected = [
			[
				'name' => 'field_3',
				'#type' => 'text',
			],
			[
				'name' => 'field_2',
				'#type' => 'text',
			],
			[
				'name' => 'field_1',
				'#type' => 'text',
			],
		];

		$this->registerTestingEvent('fields', 'object:foo_bar3', function () use ($fields) {
			return $fields;
		});

		$this->assertEquals($expected, elgg()->fields->get('object', 'foo_bar3'));
	}
}
