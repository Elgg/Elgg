<?php
namespace Elgg\Assets;


class ExternalFilesTest extends \PHPUnit_Framework_TestCase {

	public function testPreservesInputConfigData() {
		$config = new \stdClass();
		$list = new \ElggPriorityList();
		$obj1 = (object)array(
			'name' => 'bar1',
			'url' => '#',
			'loaded' => false,
			'location' => 'custom_location'
		);
		$obj2 = (object)array(
			'name' => 'bar2',
			'url' => 'http://elgg.org/',
			'loaded' => true,
			'location' => 'custom_location'
		);

		$list->add($obj1, 600);
		$list->add($obj2, 300);
		$config->externals = array(
			'foo' => $list
		);
		$config->externals_map = array(
			'foo' => array(
				'bar1' => $obj1,
				'bar2' => $obj2,
			)
		);

		$externalFiles = new \Elgg\Assets\ExternalFiles($config);

		$this->assertEquals(array(
			300 => 'http://elgg.org/'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar1');

		$this->assertEquals(array(
			300 => 'http://elgg.org/',
			600 => '#'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));
	}

	public function testRegisterItemsAndLoad() {
		$externalFiles = new \Elgg\Assets\ExternalFiles();

		$externalFiles->load('foo', 'bar2');

		$externalFiles->register('foo', 'bar1', '#', 'custom_location', 600);
		$externalFiles->register('foo', 'bar2', 'http://www.elgg.org/', '', 200);
		$externalFiles->register('foo', 'bar2', 'http://elgg.org/', 'custom_location', 300);
		$externalFiles->register('foo', 'bar3', 'http://community.elgg.org/', 'custom_location', 'abc');

		$this->assertFalse($externalFiles->register('foo', '', 'ipsum', 'dolor'));
		$this->assertFalse($externalFiles->register('foo', 'lorem', '', 'dolor'));

		$this->assertEquals(array(
			300 => 'http://elgg.org/'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar1');

		$this->assertEquals(array(
			300 => 'http://elgg.org/',
			600 => '#'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar3');

		$this->assertEquals(array(
			300 => 'http://elgg.org/',
			500 => 'http://community.elgg.org/',
			600 => '#'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$this->assertTrue($externalFiles->unregister('foo', 'bar1'));

		$this->assertEquals(array(
			300 => 'http://elgg.org/',
			500 => 'http://community.elgg.org/'
		), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$this->assertFalse($externalFiles->unregister('foo', 'bar1'));

		$externalFiles->load('foo', 'bar5');

		$this->assertEquals(array(
			0 => ''
		), $externalFiles->getLoadedFiles('foo', ''));

		$this->assertEquals(array(), $externalFiles->getLoadedFiles('nonexistent', 'custom_location'));
	}
}

