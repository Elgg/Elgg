<?php

namespace Elgg\Email;

use Elgg\UnitTestCase;
use Laminas\Mime\Mime;

/**
 * @group EmailService
 * @group UnitTests
 */
class AttachmentUnitTest extends UnitTestCase {
	
	/**
	 * @var \ElggFile
	 */
	protected $file;
	
	/**
	 * @var array
	 */
	protected $attachment;
	
	public function up() {
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');

		$this->assertTrue($file->exists());
		
		$this->file = $file;
		
		$this->attachment = [
			'content' => 'Test file content',
			'filename' => 'text.txt',
			'type' => 'text/plain',
		];
	}

	public function down() {

	}
	
	public function testFactoryFromArray() {
		
		$attachment = Attachment::factory($this->attachment);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals($this->attachment['content'], $attachment->getContent());
		$this->assertEquals($this->attachment['type'], $attachment->getType());
		$this->assertEquals($this->attachment['filename'], $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getId());
	}
	
	public function testFactoryFromInvalidArray() {
		
		$invalid_array = $this->attachment;
		unset($invalid_array['content']);
		
		_elgg_services()->logger->disable();
		
		$attachment = Attachment::factory($invalid_array);
		
		_elgg_services()->logger->enable();
		
		$this->assertFalse($attachment);
	}
	
	public function testFactoryFromElggFile() {
		
		$attachment = Attachment::factory($this->file);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals(Mime::ENCODING_BASE64, $attachment->getEncoding());
		$this->assertEquals($this->file->grabFile(), base64_decode($attachment->getContent()));
		$this->assertEquals($this->file->getMimeType(), $attachment->getType());
		$this->assertEquals($this->file->getFilename(), $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getId());
	}
	
	public function testFromElggFile() {
		
		$attachment = Attachment::fromElggFile($this->file);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals(Mime::ENCODING_BASE64, $attachment->getEncoding());
		$this->assertEquals($this->file->grabFile(), base64_decode($attachment->getContent()));
		$this->assertEquals($this->file->getMimeType(), $attachment->getType());
		$this->assertEquals($this->file->getFilename(), $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getId());
	}
	
	public function testFromInvalidElggFile() {
		
		$invalid_file = $this->file;
		$invalid_file->setFilename('foobar2.txt');
		
		$this->assertFalse($invalid_file->exists());
		
		_elgg_services()->logger->disable();
		
		$attachment = Attachment::fromElggFile($this->file);
		
		_elgg_services()->logger->enable();
		
		$this->assertFalse($attachment);
	}
	
	public function testUniqueID() {
		
		$attachment = Attachment::factory($this->attachment);
		$attachment2 = Attachment::factory($this->attachment);
		
		$this->assertNotFalse($attachment);
		$this->assertNotFalse($attachment2);
		
		$this->assertNotEmpty($attachment->getId());
		$this->assertNotEmpty($attachment2->getId());
		
		$this->assertNotEquals($attachment->getId(), $attachment2->getId());
	}
	
	public function testSetID() {
		
		$options = $this->attachment;
		$options['id'] = 'my_custom_id';
		
		$attachment = Attachment::factory($options);
		
		$this->assertNotFalse($attachment);
		
		$this->assertNotEmpty($attachment->getId());
		$this->assertEquals($options['id'], $attachment->getId());
	}
}
