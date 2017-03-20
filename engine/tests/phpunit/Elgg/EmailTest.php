<?php

namespace Elgg;

/**
 * @group EmailService
 */
class EmailTest extends TestCase {

	public function setUp() {
		$this->setupMockServices();
	}

	public function testFactoryFromElggUser() {

		$from = $this->mocks()->getUser([
			'email' => 'from@elgg.org',
			'name' => 'From',
		]);
		
		$to = $this->mocks()->getUser([
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
		$this->assertEquals(new \Zend\Mail\Address($site->getEmailAddress(), $site->getDisplayName()), $email->getFrom());
		$this->assertEquals(new \Zend\Mail\Address($to->email, $to->getDisplayName()), $email->getTo());
	}

	public function testFactoryFromEmailString() {

		$email = Email::factory([
			'from' => "from@elgg.org",
			'to' => "to@elgg.org",
			'subject' => '',
			'body' => '',
		]);

		$this->assertEquals(Email::fromString("from@elgg.org"), $email->getFrom());
		$this->assertEquals(Email::fromString("to@elgg.org"), $email->getTo());
	}

	public function testFactoryFromContactString() {

		$email = Email::factory([
			'from' => "From <from@elgg.org>",
			'to' => "To <to@elgg.org>",
			'subject' => '',
			'body' => '',
		]);

		$this->assertEquals(new \Zend\Mail\Address("from@elgg.org", "From"), $email->getFrom());
		$this->assertEquals(new \Zend\Mail\Address("to@elgg.org", "To"), $email->getTo());
	}

	public function testFactory() {

		$from =  new \Zend\Mail\Address('from@elgg.org', 'From');
		$to = new \Zend\Mail\Address('to@elgg.org', 'to');

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

}
