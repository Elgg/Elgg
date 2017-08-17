<?php

namespace Elgg\Json;

/**
 * @group UnitTests
 */
class EmptyKeyEncodingUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	function testRoundTrip() {
		$json = <<<EOL
{
    "": [
    	{
			"autoload": {
				"psr-0": {
					"": "engine/classes/"
				}
			}
    	}
    ],
    "foo": true
}
EOL;

		$encoding = new EmptyKeyEncoding();
		$value = $encoding->decode($json);
		$empty_key = $encoding->getEmptyKey();

		$this->assertTrue(is_array($value->{$empty_key}));
		$this->assertFalse(property_exists($value, '_empty_'));
		$this->assertEquals('engine/classes/', $value->{$empty_key}[0]->autoload->{'psr-0'}->{$empty_key});

		$json = $encoding->encode($value, JSON_UNESCAPED_SLASHES);
		$this->assertContains('"":"engine/classes/"', $json);
		$this->assertContains('"":[{"autoload"', $json);
		$this->assertNotContains($empty_key, $json);
	}

	function testEncodeWithGivenKey() {
		$key = 'gyufg78r3gyfryu';
		$value = array(
			$key => 'foo',
		);
		$encoding = new EmptyKeyEncoding($key);
		$json = $encoding->encode($value);

		$this->assertEquals('{"":"foo"}', $json);
	}

}
