<?php
namespace Elgg\Mail;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mail\Transport\InMemory as InMemoryTransport;

class MailerTest extends TestCase {
	
	function testElggSendEmailPassesAllFieldsAsMessageToMailer() {
		$mailer = new InMemoryTransport();
		_elgg_services()->setValue('mailer', $mailer);
		
		elgg_send_email("From <from@elgg.org>", "To <to@elgg.org>", "Dummy subject", "Dummy body");
		
		$message = $mailer->getLastMessage();
		
		$this->assertEquals('To', $message->getTo()->get('to@elgg.org')->getName());
		$this->assertEquals('From', $message->getFrom()->get('from@elgg.org')->getName());
		$this->assertEquals("Dummy subject", $message->getSubject());
		$this->assertEquals("Dummy body", $message->getBodyText());
	}
	
}

