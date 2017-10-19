<?php

namespace Elgg;

use Elgg\Email\Address;

/**
 * @group EmailService
 * @group UnitTests
 */
class EmailUnitTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testFactoryFromElggUser() {

		$from = $this->createUser([], [
			'email' => 'from@elgg.org',
			'name' => 'From',
		]);
		
		$to = $this->createUser([], [
			'email' => 'to@elgg.org',
			'name' => 'To',
		]);
		
		$email = Email::factory([
			'from' => $from,
			'to' => $to,
			'subject' => '',
			'body' => '',
		]);

		// We never send email from users
		$site = elgg_get_site_entity();
		$this->assertEquals(new Address($site->getEmailAddress(), $site->getDisplayName()), $email->getFrom());
		$this->assertEquals(new Address($to->email, $to->getDisplayName()), $email->getTo());
	}

	public function testFactoryFromEmailString() {

		$email = Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => '',
			'body' => '',
		]);

		$this->assertEquals(Address::fromString('from@elgg.org'), $email->getFrom());
		$this->assertEquals(Address::fromString('to@elgg.org'), $email->getTo());
	}

	public function testFactoryFromContactString() {

		$email = Email::factory([
			'from' => 'From <from@elgg.org>',
			'to' => 'To <to@elgg.org>',
			'subject' => '',
			'body' => '',
		]);

		$this->assertEquals(new Address('from@elgg.org', 'From'), $email->getFrom());
		$this->assertEquals(new Address('to@elgg.org', 'To'), $email->getTo());
	}

	public function testFactory() {

		$from = new Address('from@elgg.org', 'From');
		$to = new Address('to@elgg.org', 'to');

		$email = Email::factory([
			'from' => $from,
			'to' => $to,
			'subject' => 'Subject',
			'body' => 'Body',
			'headers' => [
				'Foo' => 'Bar',
			],
			'params' => [
				'Baz' => 1,
			],
		]);

		$email->addHeader('Foo2', 'Bar2');

		$this->assertEquals($from, $email->getFrom());
		$this->assertEquals($to, $email->getTo());
		$this->assertEquals('Subject', $email->getSubject());
		$this->assertEquals('Body', $email->getBody());
		$this->assertEquals('Subject', $email->getSubject());
		$this->assertEquals(['Foo' => 'Bar', 'Foo2' => 'Bar2'], $email->getHeaders());
		$this->assertEquals(['Baz' => 1], $email->getParams());
	}

	function testFactoryAddAttachmentFromParams() {
		
		$email = Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => '',
			'body' => '',
			'params' => [
				'attachments' => [
					[
						'content' => 'Test file content',
						'filename' => 'test.txt',
						'type' => 'text/plain',
					],
				]
			]
		]);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(1, $email->getAttachments());
	}
	
	function testFactoryAddAttachmentsFromParams() {
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');
		
		$this->assertTrue($file->exists());
		
		$email = Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => '',
			'body' => '',
			'params' => [
				'attachments' => [
					[
						'content' => 'Test file content',
						'filename' => 'test.txt',
						'type' => 'text/plain',
					],
					$file,
				]
			]
		]);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(2, $email->getAttachments());
	}
	
	function testFactoryAddAttachments() {
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');
		
		$this->assertTrue($file->exists());
		
		$email = Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => '',
			'body' => '',
			'params' => [
				'attachments' => [
					[
						'content' => 'Test file content',
						'filename' => 'test.txt',
						'type' => 'text/plain',
					],
					$file,
				]
			]
		]);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(2, $email->getAttachments());
		
		$new_attachment = [
			'content' => 'Test file content 2',
			'filename' => 'test2.txt',
			'type' => 'text/plain',
		];
		
		$email->addAttachment($new_attachment);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(3, $email->getAttachments());
	}
	
	function testAddAttachmentFromPart() {
		
		$email = new Email();
		
		$part = new \Zend\Mime\Part('Test file content');
		$part->type = 'text/plain';
		$part->disposition = 'attachment';
		
		$email->addAttachment($part);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(1, $email->getAttachments());
		
		$email_parts = $email->getAttachments();
		$email_part = $email_parts[0];
		
		$this->assertEquals($part, $email_part);
		$this->assertEquals($part->getContent(), $email_part->getContent());
		$this->assertEquals($part->getType(), $email_part->getType());
		$this->assertEquals($part->getDisposition(), $email_part->getDisposition());
	}
	
	function testAddAttachmentFromElggFile() {
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');
		
		$this->assertTrue($file->exists());
		
		$email = new Email();
		
		$email->addAttachment($file);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(1, $email->getAttachments());
		
		$email_parts = $email->getAttachments();
		$email_part = $email_parts[0];
		
		$this->assertEquals($file->grabFile(), $email_part->getContent());
		$this->assertEquals($file->getMimeType(), $email_part->getType());
		$this->assertEquals($file->getFilename(), $email_part->getFileName());
	}
	
	function testAddAttachmentFromArray() {
		
		$attachment = [
			'content' => 'Test file content',
			'filename' => 'test.txt',
			'type' => 'text/plain',
		];
		
		$email = new Email();
		
		$email->addAttachment($attachment);
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(1, $email->getAttachments());
		
		$email_parts = $email->getAttachments();
		$email_part = $email_parts[0];
		
		$this->assertEquals($attachment['content'], $email_part->getContent());
		$this->assertEquals($attachment['type'], $email_part->getType());
		$this->assertEquals($attachment['filename'], $email_part->getFileName());
	}
	
	function testAddInvalidAttachmentFromArray() {
		
		$attachment = [
			'filename' => 'test.txt',
			'type' => 'text/plain',
		];
		
		$email = new Email();
		
		_elgg_services()->logger->disable();
		
		$email->addAttachment($attachment);
		
		_elgg_services()->logger->enable();
		
		$this->assertInternalType('array', $email->getAttachments());
		$this->assertCount(0, $email->getAttachments());
	}
}
