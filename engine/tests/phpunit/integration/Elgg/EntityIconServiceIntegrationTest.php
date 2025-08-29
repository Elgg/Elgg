<?php

namespace Elgg;

class EntityIconServiceIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
	}
	
	/**
	 * @dataProvider invalidCoordinatesProvider
	 */
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
	
	/**
	 * @dataProvider detectCroppingCoordinatesDataProvider
	 */
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
