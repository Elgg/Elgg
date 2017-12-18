<?php

namespace Elgg\Di;

use Elgg\Config;
use Elgg\Database\SiteSecret;
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

	public function testCanExtractSiteSecretFromConfig() {
		$config = new Config([
			SiteSecret::CONFIG_KEY => md5('bar'),
		]);
		$sp = new ServiceProvider($config);

		$this->assertEmpty($config->{SiteSecret::CONFIG_KEY});

		$this->assertInstanceOf(SiteSecret::class, $sp->siteSecret);
		$this->assertEquals(md5('bar'), $sp->siteSecret->get());
	}

	public function testSetsBackupSiteSecretFactory() {
		$config_table = _elgg_services()->configTable;
		$config_table->set(SiteSecret::CONFIG_KEY, md5('foo'));

		$config = new Config();
		$sp = new ServiceProvider($config);
		$sp->setValue('configTable', $config_table);

		$this->assertInstanceOf(SiteSecret::class, $sp->siteSecret);
		$this->assertEquals(md5('foo'), $sp->siteSecret->get());
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
