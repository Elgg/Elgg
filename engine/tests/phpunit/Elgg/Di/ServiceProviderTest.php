<?php
namespace Elgg\Di;

use phpDocumentor\Reflection\DocBlock;
use Zend\Mail\Transport\InMemory;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$sp = _elgg_services();
		$sp->setValue('session', \ElggSession::getMock());
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
		$this->assertInstanceOf($type, $obj1);

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

		foreach ($sp->getNames() as $name) {
			if (isset($list[$name])) {
				continue;
			}
			$this->fail("$name is not present in data provider");
		}
	}

	public static function servicesListProvider() {
		$sp = _elgg_services();
		$class = new \ReflectionClass(get_class($sp));
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
