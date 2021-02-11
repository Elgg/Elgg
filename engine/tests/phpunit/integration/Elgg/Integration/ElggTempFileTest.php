<?php

namespace Elgg\Integration;

use Elgg\Exceptions\Filesystem\IOException;
use Elgg\IntegrationTestCase;
use ElggTempFile;

/**
 * Elgg Test Skeleton
 *
 * @group IntegrationTests
 */
class ElggTempFileTest extends IntegrationTestCase {

	/**
	 * @var \ElggTempFile
	 */
	protected $temp_file;
	
	public function up() {
		$this->temp_file = new ElggTempFile();
	}

	public function down() {
		
		if (isset($this->temp_file)) {
			$this->temp_file->delete();
			unset($this->temp_file);
		}
	}

	public function testInitialFilename() {
		
		$this->assertNotEmpty($this->temp_file->getFilename());
	}
	
	public function testFilenameInSystemTempFolder() {
		
		$this->assertStringStartsWith(sys_get_temp_dir(), $this->temp_file->getFilenameOnFilestore());
	}
	
	public function testWriteContent() {
		
		$temp_file = $this->temp_file;
		
		$this->assertTrue(is_resource($temp_file->open('write')));
		$this->assertNotFalse($temp_file->write('1234'));
		$this->assertTrue($temp_file->close());
		
		$this->assertEquals('1234', $temp_file->grabFile());
	}
	
	public function testDeleteFile() {
		
		$temp_file = $this->temp_file;
		
		$this->assertTrue(is_resource($temp_file->open('write')));
		$this->assertNotFalse($temp_file->write('1234'));
		$this->assertTrue($temp_file->close());
		
		$this->assertTrue($temp_file->exists());
		
		$this->assertTrue($temp_file->delete());
		
		$this->assertFalse($temp_file->exists());
	}
	
	public function testFilenameUniqueness() {
		
		$temp1 = new ElggTempFile();
		$temp2 = new ElggTempFile();
		
		$this->assertNotEquals($temp1->getFilenameOnFilestore(), $temp2->getFilenameOnFilestore());
	}
	
	public function testChangeFilename() {
		
		$temp_file = $this->temp_file;
		
		$initial_filename = $temp_file->getFilename();
		
		$temp_file->setFilename('testing');
		
		$this->assertEquals('testing', $temp_file->getFilename());
		
		$this->assertStringNotContainsString($initial_filename, $temp_file->getFilenameOnFilestore());
		$this->assertStringContainsString('testing', $temp_file->getFilenameOnFilestore());
		
		$this->assertTrue(is_resource($temp_file->open('write')));
		$this->assertNotFalse($temp_file->write('1234'));
		$this->assertTrue($temp_file->close());
		
		$this->assertTrue($temp_file->exists());
	}
	
	public function testUnsupportedFunctions() {
		
		$temp_file = $this->temp_file;
		
		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		$this->assertFalse($temp_file->canDownload());
		$this->assertFalse($temp_file->transfer($user->guid));
		$this->assertEquals('', $temp_file->getDownloadURL());
		$this->assertEquals('', $temp_file->getInlineURL());
		
		_elgg_services()->session->removeLoggedInUser();
		$user->delete();
	}
	
	public function testSaveThrowsException() {
		$this->expectException(IOException::class);
		$this->temp_file->save();
	}
	
	public function testLibFunctionToGetTempFile() {
		
		$this->assertInstanceOf(ElggTempFile::class, elgg_get_temp_file());
	}
}
