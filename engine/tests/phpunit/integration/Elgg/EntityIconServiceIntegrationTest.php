<?php

namespace Elgg;

use PHPUnit\Framework\Attributes\DataProvider;

class EntityIconServiceIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
	}

	/**
	 * test \Elgg\Icons\TouchIconsOnAccessChangeHandler does it's job
	 */
	public function testIconURLInvalidatedOnAccessIdChange() {
		_elgg_services()->session_manager->setLoggedInUser($this->createUser());
		
		$entity = $this->createObject([
			'access_id' => ACCESS_PRIVATE,
		]);
		
		$this->assertTrue($entity->saveIconFromLocalFile($this->normalizeTestFilePath('dataroot/1/1/300x300.jpg')));
		$icon_url = $entity->getIconURL('medium');
		$this->assertNotEmpty($icon_url);
		$this->assertEquals($icon_url, $entity->getIconURL('medium')); // test it does not change
		
		sleep(1); // wait so access_id touch will update to a newer timestamp
		
		$entity->access_id = ACCESS_PUBLIC;
		$this->assertTrue($entity->save());
		$this->assertNotEquals($icon_url, $entity->getIconURL('medium')); // test it does change
	}

	#[DataProvider('invalidCoordinatesProvider')]
	public function testInvalidDetectCroppingCoordinates($input_name, $params) {
		$request = $this->prepareHttpRequest('', 'POST', $params);
		
		_elgg_services()->set('request', $request);
		
		$service = _elgg_services()->iconService;
		
		$this->assertNull($this->invokeInaccessableMethod($service, 'detectCroppingCoordinates', $input_name));
	}
	
	public static function invalidCoordinatesProvider() {
		return [
			['foo', []], // no input
			['foo', ['x1' => '1', 'x2' => '100', 'y1' => '10']], // missing one coordinate (using fallback coords)
			['foo', ['foo_x1' => '1', 'foo_x2' => '100', 'foo_y1' => '10']], // missing one coordinate
			['foo', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => 'string']], // one invalid coordinate (using fallback coords)
			['foo', ['foo_x1' => '1', 'foo_x2' => '100', 'foo_y1' => '10', 'foo_y2' => 'string']], // one invalid coordinate
			['foo', ['x1' => '100', 'x2' => '1', 'y1' => '10', 'y2' => '100']], // x2 < x1 (using fallback coords)
			['foo', ['foo_x1' => '100', 'foo_x2' => '1', 'foo_y1' => '10', 'foo_y2' => '100']], // x2 < x1
			['foo', ['x1' => '10', 'x2' => '100', 'y1' => '100', 'y2' => '10']], // y2 < y1 (using fallback coords)
			['foo', ['foo_x1' => '10', 'foo_x2' => '100', 'foo_y1' => '100', 'foo_y2' => '10']], // y2 < y1
		];
	}

	#[DataProvider('detectCroppingCoordinatesDataProvider')]
	public function testDetectCroppingCoordinates($input_name, $params, $expected_value) {
		$request = $this->prepareHttpRequest('', 'POST', $params);
		
		_elgg_services()->set('request', $request);

		$result = $this->invokeInaccessableMethod(_elgg_services()->iconService, 'detectCroppingCoordinates', $input_name);
		
		$this->assertEquals($expected_value, $result);
	}
	
	public static function detectCroppingCoordinatesDataProvider() {
		return [
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]],
			['icon', ['x1' => '1.0', 'x2' => '100.2', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // handle floats
			['icon', ['icon_x1' => '1', 'icon_x2' => '100', 'icon_y1' => '10', 'icon_y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]],
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // test BC input is used
			['icon', ['icon_x1' => '1', 'icon_x2' => '100', 'icon_y1' => '10', 'icon_y2' => '100', 'x1' => '10', 'x2' => '1000', 'y1' => '100', 'y2' => '1000'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // test BC input isn't used
		];
	}
}
