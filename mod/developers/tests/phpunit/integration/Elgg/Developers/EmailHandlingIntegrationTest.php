<?php

namespace Elgg\Developers;

use Elgg\Email;
use Elgg\Event;
use Elgg\Plugins\IntegrationTestCase;

class EmailHandlingIntegrationTest extends IntegrationTestCase {
	
	protected ?\ElggPlugin $plugin = null;
	
	protected ?string $forward_email = null;
	
	protected ?string $block_email = null;
	
	public function up() {
		$this->plugin = elgg_get_plugin_from_id('developers');
		
		$this->forward_email = $this->plugin->forward_email;
		$this->block_email = $this->plugin->block_email;
	}
	
	public function down() {
		$this->plugin->setSetting('forward_email', $this->forward_email);
		$this->plugin->setSetting('block_email', $this->block_email);
		
		parent::down();
	}
	
	protected function prepareEmail(): Email {
		$user = $this->createUser();
		$admin = $this->createUser([
			'admin' => true,
		]);
		return Email::factory([
			'subject' => 'Testing',
			'body' => 'Just testing',
			'to' => $user,
			'cc' => $admin,
			'bcc' => 'bcc@example.com',
		]);
	}
	
	public function testSetForwardAddressNotEnabled() {
		$this->plugin->unsetSetting('block_email');
		
		$email = $this->prepareEmail();
		$event = new Event(elgg(), 'prepare', 'system:email', $email);
		
		$result = Events::setForwardEmailAddress($event);
		
		$this->assertNull($result);
	}
	
	public function testSetForwardAddressInvalidForwardAddress() {
		$this->plugin->setSetting('block_email', 'forward');
		$this->plugin->setSetting('forward_email', 'forward');
		
		$email = $this->prepareEmail();
		$event = new Event(elgg(), 'prepare', 'system:email', $email);
		
		$result = Events::setForwardEmailAddress($event);
		
		$this->assertNull($result);
	}
	
	public function testSetForwardAddressNoEmailProvided() {
		$this->plugin->setSetting('block_email', 'forward');
		$this->plugin->setSetting('forward_email', 'forward@example.com');
		
		$event = new Event(elgg(), 'prepare', 'system:email');
		
		$result = Events::setForwardEmailAddress($event);
		
		$this->assertNull($result);
	}
	
	public function testSetForwardAddress() {
		$this->plugin->setSetting('block_email', 'forward');
		$this->plugin->setSetting('forward_email', 'forward@example.com');
		
		$email = $this->prepareEmail();
		$event = new Event(elgg(), 'prepare', 'system:email', $email);
		
		$result = Events::setForwardEmailAddress($event);
		
		$this->assertInstanceOf(Email::class, $result);
		
		$addresses = $result->getTo();
		$this->assertCount(1, $addresses);
		$this->assertStringContainsString('forward@example.com', $addresses[0]->getEmail());
		
		$addresses = $result->getCc();
		$this->assertCount(1, $addresses);
		$this->assertStringContainsString('forward@example.com', $addresses[0]->getEmail());
		
		$addresses = $result->getBcc();
		$this->assertCount(1, $addresses);
		$this->assertStringContainsString('forward@example.com', $addresses[0]->getEmail());
	}
	
	public function testBlockOutgoingEmailsNotEnabled() {
		$this->plugin->unsetSetting('block_email');
		
		$email = $this->prepareEmail();
		$event = new Event(elgg(), 'transport', 'system:email', false, ['email' => $email]);
		
		$result = Events::blockOutgoingEmails($event);
		
		$this->assertNull($result);
	}
	
	public function testBlockOutgoingEmailsAllUsers() {
		$this->plugin->setSetting('block_email', 'all');
		
		$email = $this->prepareEmail();
		$event = new Event(elgg(), 'transport', 'system:email', false, ['email' => $email]);
		
		$result = Events::blockOutgoingEmails($event);
		
		$this->assertTrue($result);
	}
	
	public function testBlockOutgoingNoEmailProvided() {
		$this->plugin->setSetting('block_email', 'users');
		
		$event = new Event(elgg(), 'transport', 'system:email', false);
		
		$result = Events::blockOutgoingEmails($event);
		
		$this->assertNull($result);
	}
	
	public function testBlockOutgoingAdminOnly() {
		$this->plugin->setSetting('block_email', 'users');
		
		$email = $this->prepareEmail();
		$original_email = clone $email;
		$event = new Event(elgg(), 'transport', 'system:email', false, ['email' => $email]);
		
		$result = Events::blockOutgoingEmails($event);
		
		$this->assertNull($result);
		
		// normal user should be filtered out
		$this->assertEmpty($email->getTo());
		
		// admin should still be there
		$this->assertCount(1, $original_email->getCc());
		$this->assertEquals($original_email->getCc(), $email->getCc());
		
		// random email addres should be filtered out
		$this->assertEmpty($email->getBcc());
	}
}
