<?php

namespace Elgg\Email;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

class AttachmentUnitTest extends UnitTestCase {
	
	protected ?\ElggFile $file = null;
	
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
	
	public function testFactoryFromArray() {
		$attachment = Attachment::factory($this->attachment);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals($this->attachment['content'], $attachment->getBody());
		$this->assertEquals($this->attachment['type'], $attachment->getContentType());
		$this->assertEquals($this->attachment['filename'], $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getContentId());
	}
	
	public function testFactoryFromInvalidArray() {
		$invalid_array = $this->attachment;
		unset($invalid_array['content']);
		
		$this->expectException(InvalidArgumentException::class);
		Attachment::factory($invalid_array);
	}
	
	public function testFactoryFromElggFile() {
		$attachment = Attachment::factory($this->file);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals($this->file->grabFile(), base64_decode($attachment->bodyToString()));
		$this->assertEquals($this->file->getMimeType(), $attachment->getContentType());
		$this->assertEquals($this->file->getFilename(), $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getContentId());
	}
	
	public function testFromElggFile() {
		$attachment = Attachment::fromElggFile($this->file);
		
		$this->assertNotFalse($attachment);
		
		$this->assertEquals($this->file->grabFile(), base64_decode($attachment->bodyToString()));
		$this->assertEquals($this->file->getMimeType(), $attachment->getContentType());
		$this->assertEquals($this->file->getFilename(), $attachment->getFileName());
		$this->assertEquals('attachment', $attachment->getDisposition());
		$this->assertNotEmpty($attachment->getContentId());
	}
	
	public function testFromInvalidElggFile() {
		$invalid_file = $this->file;
		$invalid_file->setFilename('foobar2.txt');
		
		$this->assertFalse($invalid_file->exists());
		
		$this->expectException(InvalidArgumentException::class);
		Attachment::fromElggFile($this->file);
	}
	
	public function testUniqueID() {
		$attachment = Attachment::factory($this->attachment);
		$attachment2 = Attachment::factory($this->attachment);
		
		$this->assertNotEmpty($attachment->getContentId());
		$this->assertNotEmpty($attachment2->getContentId());
		
		$this->assertNotEquals($attachment->getContentId(), $attachment2->getContentId());
	}
	
	public function testSetID() {
		$options = $this->attachment;
		$options['id'] = 'my_custom_id';
		
		$attachment = Attachment::factory($options);
		
		$this->assertNotEmpty($attachment->getContentId());
		$this->assertStringStartsWith($options['id'], $attachment->getContentId());
	}
}
