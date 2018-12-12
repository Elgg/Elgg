<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCaseTest;

class EntityIconServiceIntegrationTest extends IntegrationTestCaseTest {
	
	/**
	 * @dataProvider detectCroppingCoordinatesDataProvider
	 */
	public function testDetectCroppingCoordinates($params, $expected_value) {
		$request = $this->prepareHttpRequest('', 'POST', $params);
		
		_elgg_services()->setValue('request', $request);
		
		$reflector = new \ReflectionClass(_elgg_services()->iconService);
		$method = $reflector->getMethod('detectCroppingCoordinates');
		$method->setAccessible(true);
		
		$result = $method->invoke(_elgg_services()->iconService);
		
		$this->assertEquals($expected_value, $result);
	}
	
	public function detectCroppingCoordinatesDataProvider() {
		return [
			[[], false], // no input
			[['x1' => '1', 'x2' => '100', 'y1' => '10'], false], // missing one coordinate
			[['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => 'string'], false], // one invalid coordinate
			[['x1' => '1', 'x2' => '100', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]],
			[['x1' => '1.0', 'x2' => '100.2', 'y1' => '10', 'y2' => '100'], ['x1' => 1, 'x2' => 100, 'y1' => 10, 'y2' => 100]], // handle floats
		];
	}
}
