<?php

namespace Elgg\Di;

use phpDocumentor\Reflection\DocBlock;
use Zend\Mail\Transport\InMemory;

/**
 * @group Application
 * @group UnitTests
 */
class ServiceProviderUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @dataProvider servicesListProvider
	 */
	public function testPropertyType($name, $type) {
		$sp = _elgg_services();

		$non_shared_names = [
			'queryCounter',
		];

		$skipped_names = [
				//'service' => 'reason can't be loaded in phpunit',
		];

		if (isset($skipped_names[$name])) {
			$this->markTestSkipped($skipped_names[$name]);
			return;
		}

		$obj1 = $sp->{$name};
		$obj2 = $sp->{$name};

		// support $type like "Foo\Bar|Baz|null"
		$passed = false;
		foreach (explode('|', $type) as $test_type) {
			if ($test_type === 'null') {
				if ($obj1 === null) {
					$passed = true;
				}
			} elseif ($obj1 instanceof $test_type) {
				$passed = true;
			}
		}
		$this->assertTrue($passed, "\$obj1 did not match type $type");

		if (in_array($name, $non_shared_names)) {
			$this->assertNotSame($obj1, $obj2);
		} else {
			$this->assertSame($obj1, $obj2);
		}
	}

	public function testListProvider() {
		$sp = _elgg_services();

		$list = [];
		foreach (self::servicesListProvider() as $item) {
			$list[$item[0]] = $item[1];
		}

		$errors = [];
		foreach ($sp->getNames() as $name) {
			if (isset($list[$name])) {
				continue;
			}

			$errors[] = "$name is not present in data provider";
		}

		if ($errors) {
			$this->fail(implode(PHP_EOL, $errors));
		}
	}

	public static function servicesListProvider() {
		$class = new \ReflectionClass(ServiceProvider::class);
		$phpdoc = new DocBlock($class);
		$readonly_props = $phpdoc->getTagsByName('property-read');

		/* @var \phpDocumentor\Reflection\DocBlock\Tag\PropertyReadTag[] $readonly_props */
		foreach ($readonly_props as $prop) {
			$name = substr($prop->getVariableName(), 1);
			$type = $prop->getType();

			// stuff set in PHPUnit bootstrap
			if ($name === 'mailer') {
				$type = InMemory::class;
			}

			$sets[] = [$name, $type];
		}

		return $sets;
	}

}
