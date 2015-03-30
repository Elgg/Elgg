<?php
namespace Elgg\Filesystem;
use Elgg\Filesystem\Adapter\Adapter;
use Elgg\Filesystem\AdapterService;
use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\InMemory as GaufretteMemory;

class DiskTest extends \PHPUnit_Framework_TestCase {
	
	function setUp() {
		$args = [
			'root_dir' => '/test/data_dir'
		];
		$mock = $this->getMock('\Elgg\Filesystem\Adapter\Disk', [], [], '', false);
		
//		$mock->expects($matcher)
		
//			->expects($this->any())
//			->will($this->returnCallback($mock));
	}
}
