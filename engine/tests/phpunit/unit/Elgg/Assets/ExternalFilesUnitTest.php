<?php

namespace Elgg\Assets;

/**
 * @group UnitTests
 */
class ExternalFilesUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testPreservesInputConfigData() {
		$externalFiles = new \Elgg\Assets\ExternalFiles();
		$externalFiles->register('foo', 'bar1', '#', 'custom_location');
		$externalFiles->register('foo', 'bar2', 'http://elgg.org/', 'custom_location');
		$externalFiles->load('foo', 'bar2');

		$this->assertEquals(array(
			'bar2' => 'http://elgg.org/'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar1');

		$this->assertEquals(array(
			'bar1' => '#',
			'bar2' => 'http://elgg.org/'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));
	}

	public function testRegisterItemsAndLoad() {
		$externalFiles = new \Elgg\Assets\ExternalFiles();

		$externalFiles->load('foo', 'bar2');

		$externalFiles->register('foo', 'bar1', '#', 'custom_location');
		$externalFiles->register('foo', 'bar2', 'http://www.elgg.org/', '');
		$externalFiles->register('foo', 'bar2', 'http://elgg.org/', 'custom_location');
		$externalFiles->register('foo', 'bar3', 'http://community.elgg.org/', 'custom_location', 'abc');

		$this->assertFalse($externalFiles->register('foo', '', 'ipsum', 'dolor'));
		$this->assertFalse($externalFiles->register('foo', 'lorem', '', 'dolor'));

		$this->assertEquals(array(
			'bar2' => 'http://elgg.org/'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar1');

		$this->assertEquals(array(
			'bar2' => 'http://elgg.org/',
			'bar1' => '#'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$externalFiles->load('foo', 'bar3');

		$this->assertEquals(array(
			'bar2' => 'http://elgg.org/',
			'bar1' => '#',
			'bar3' => 'http://community.elgg.org/'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$this->assertTrue($externalFiles->unregister('foo', 'bar1'));

		$this->assertEquals(array(
			'bar2' => 'http://elgg.org/',
			'bar3' => 'http://community.elgg.org/'
				), $externalFiles->getLoadedFiles('foo', 'custom_location'));

		$this->assertFalse($externalFiles->unregister('foo', 'bar1'));

		$externalFiles->load('foo', 'bar5');

		$this->assertEquals(array(
			'bar5' => ''
				), $externalFiles->getLoadedFiles('foo', ''));

		$this->assertEquals(array(), $externalFiles->getLoadedFiles('nonexistent', 'custom_location'));
	}

}
