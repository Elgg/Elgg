<?php
namespace Elgg\Filesystem;
use Elgg\Filesystem\Adapter\Adapter;
use Elgg\Filesystem\AdapterService;
use Elgg\Filesystem\Adapter\Disk;
use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\InMemory as GaufretteMemory;

class AdapterServiceTest extends \PHPUnit_Framework_TestCase {
	public function singleAdapter() {
		$l = $this->getMock('Elgg\Logger', [], [], '', false);
		$service = new AdapterService($l);

		$adapter = new inMemoryAdapter([]);
		$name = "in_memory";
		return [[$service, $adapter, $name]];
	}
	
	public function multipleAdapters() {
		$adapters = [];
		$l = $this->getMock('Elgg\Logger', [], [], '', false);
		$service = new AdapterService( $l);
		
		for ($i=1; $i<=3; $i++) {
			$adapter = new inMemoryAdapter([]);
			$name = "in_memory_$i";
			$adapters[$name] = $adapter;
		}
			
		return [[$service, $adapters]];
	}
	
	/**
	 * @dataProvider singleAdapter
	 */
	function testCanSetAdapter($s, $adp, $name) {
		$this->assertTrue($s->set($name, $adp));
	}
	
	/**
	 * @dataProvider singleAdapter
	 */
	function testSetThrowsOnSameName($s, $adp, $name) {
		$this->assertTrue($s->set($name, $adp));
		$this->setExpectedException('\UnexpectedValueException');
		$s->set($name, $adp);
	}
	
	/**
	 * @dataProvider multipleAdapters
	 */
	function testGet($s, $adapters) {
		foreach ($adapters as $name => $adapter) {
			$s->set($name, $adapter);
		}
		
		foreach ($adapters as $name => $adapter) {
			$this->assertEquals($adapter, $s->get($name));
		}
	}
	
	/**
	 * @dataProvider singleAdapter
	 */
	function testGetThrowsOnMissing($s, $adp) {
		$this->setExpectedException('\InvalidArgumentException');
		$s->get('missing');
	}
	
	/**
	 * @dataProvider singleAdapter
	 */
	function testHas($s, $adp) {
		$s->set('has', $adp);
		$this->assertTrue($s->has('has'));
		$this->assertFalse($s->has('has_not'));
	}
	
	/**
	 * @dataProvider multipleAdapters
	 */
	function testRemove($s, $adapters) {
		foreach ($adapters as $name => $adapter) {
			$s->set($name, $adapter);
		}
		
		$s->remove('in_memory_2');
		$expected = [
			'in_memory_1',
			'in_memory_3'
		];
		$this->assertEquals($expected, $s->listAdapters());
	}
	
	/**
	 * @dataProvider multipleAdapters
	 */
	function testCanListAdapters($s, $adapters) {
		foreach ($adapters as $name => $adapter) {
			$s->set($name, $adapter);
		}
		$expected = array_keys($adapters);
		$this->assertEquals($expected, $s->listAdapters());
	}
}

class inMemoryAdapter extends Disk {
	public function __construct($path) {
		$this->path = $path;

		$adapter = new GaufretteMemory($path);
		$this->fs = new Filesystem($adapter);
	}
}