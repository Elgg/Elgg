<?php
namespace Elgg;
use Elgg\DataStorgeService;

class DataStorageServiceTest extends \PHPUnit_Framework_TestCase {
	private $logger;
	private $adapter;
	
	public function setUp() {
		$this->logger = $this->getMock('Elgg\Logger', [], [], '', false);
		$this->adapter = $this->getMock('Elgg\Filesystem\Adapter\Adapter', [], [], '', false);
	}
	
	public function testCanSetAdapter() {
		$s = new DataStorageService($this->logger);
		$s->setAdapter($this->adapter);
	}
	
	public function testHasAdapter() {
		$s = new DataStorageService($this->logger);
		$this->assertFalse($s->hasAdapter());
		$s->setAdapter($this->adapter);
		$this->assertTrue($s->hasAdapter());
	}
	
	public function testFileCallsAdapter() {
		$filename = 'test_file';
		$this->adapter->expects($this->once())->method('file')
				->will($this->returnValue($filename));
		$s = new DataStorageService($this->logger);
		
		$s->setAdapter($this->adapter);
		$this->assertEquals($filename, $s->file($filename));
	}
	
	public function testDirectoryCallsAdapter() {
		$dir_name = '/test/directory';
		$this->adapter->expects($this->once())->method('directory')
				->will($this->returnValue($dir_name));
		$s = new DataStorageService($this->logger);
		
		$s->setAdapter($this->adapter);
		$this->assertEquals($dir_name, $s->directory($dir_name));
	}
}