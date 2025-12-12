<?php

namespace Elgg\Di;

use Elgg\IntegrationTestCase;
use phpDocumentor\Reflection\DocBlock\Tag;
use PHPUnit\Framework\Attributes\DataProvider;

class InternalContainerIntegrationTest extends IntegrationTestCase {

	#[DataProvider('servicesListProvider')]
	public function testPropertyType($name, $type) {
		$service = _elgg_services()->{$name};

		// support $type like "Foo\Bar|Baz|null"
		$passed = false;
		foreach (explode('|', $type) as $test_type) {
			if ($test_type === 'null') {
				if ($service === null) {
					$passed = true;
				}
			} elseif ($service instanceof $test_type) {
				$passed = true;
			}
		}
		$this->assertTrue($passed, "{$name} did not match type {$type}");
	}

	public function testListProvider() {
		$services = _elgg_services();

		$list = [];
		foreach (self::servicesListProvider() as $item) {
			$list[$item[0]] = $item[1];
		}

		$errors = [];
		foreach ($services->getKnownEntryNames() as $name) {
			if (isset($list[$name])) {
				continue;
			}
			
			if (class_exists($name) || interface_exists($name)) {
				// we only check alias names not full classes
				continue;
			}

			$errors[] = "{$name} is not present in data provider";
		}

		if ($errors) {
			$this->fail(implode(PHP_EOL, $errors));
		}
	}

	public static function servicesListProvider() {
		$class = new \ReflectionClass(InternalContainer::class);
		$factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
		$phpdoc = $factory->create($class);
		
		$readonly_props = $phpdoc->getTagsByName('property-read');
		$sets = [];
		/* @var Tag[] $readonly_props */
		foreach ($readonly_props as $prop) {
			$sets[] = [
				$prop->getVariableName(),
				$prop->getType(),
			];
		}

		return $sets;
	}
}
