<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

class EntityIconServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @dataProvider detectCroppingCoordinatesDataProvider
	 */
	public function testDetectCroppingCoordinates($input_name, $params, $expected_value) {
		$request = $this->prepareHttpRequest('', 'POST', $params);
		
		_elgg_services()->set('request', $request);
		
		$reflector = new \ReflectionClass(_elgg_services()->iconService);
		$method = $reflector->getMethod('detectCroppingCoordinates');
		$method->setAccessible(true);
		
		$result = $method->invoke(_elgg_services()->iconService, $input_name);
		
		$this->assertEquals($expected_value, $result);
	}
	
	public function detectCroppingCoordinatesDataProvider() {
		return [
			['icon', [], false], // no input
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10'], false], // missing one coordinate
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => 'string'], false], // one invalid coordinate
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]],
			['icon', ['x1' => '1.0', 'x2' => '100.2', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // handle floats
			['icon', ['icon_x1' => '1', 'icon_x2' => '100', 'icon_y1' => '10', 'icon_y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]],
			['icon', ['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // test BC input is used
			['icon', ['icon_x1' => '1', 'icon_x2' => '100', 'icon_y1' => '10', 'icon_y2' => '100', 'x1' => '10', 'x2' => '1000', 'y1' => '100', 'y2' => '1000'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // test BC input isn't used
		];
	}
}
