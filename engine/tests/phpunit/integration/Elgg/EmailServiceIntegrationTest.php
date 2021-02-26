<?php

namespace Elgg;

use Laminas\Mail\Transport\InMemory as InMemoryTransport;
use Elgg\Email\Address;

class EmailServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	public $hookArgs = [];

	/**
	 * @var InMemoryTransport
	 */
	public $mailer;

	public function up() {
		self::createApplication(['isolate'=> true]);
	}

	public function down() {
	}

	function testElggSendEmailPassesAllFieldsAsMessageToMailer() {
		$body = str_repeat("<p>You &amp; me &lt; she.</p>\n", 10);
		$body_expected = trim(wordwrap(str_repeat("You & me < she.\n", 10)));

		$subject = "<p>You &amp;\r\nme &lt;\rshe.</p>\n\n";
		$subject_expected = "You & me < she.";

		elgg_send_email(\Elgg\Email::factory([
			'from' => "Frōm <from@elgg.org>",
			'to' => "Tō <to@elgg.org>",
			'subject' => $subject,
			'body' => $body,
		]));

		$message = _elgg_services()->mailer->getLastMessage();
		
		$this->assertEquals('Tō', $message->getTo()->get('to@elgg.org')->getName());
		$this->assertEquals('Frōm', $message->getFrom()->get('from@elgg.org')->getName());
		$this->assertEquals($subject_expected, $message->getSubject());
		$this->assertEquals($body_expected, $message->getBodyText());
		$this->assertEquals('UTF-8', $message->getEncoding());
	}

	function testElggSendEmailUsesHook() {

		$calls = 0;
		$original_email = null;
		$handler = function (\Elgg\Hook $hook) use (&$original_email, &$calls) {
			$email = $hook->getValue();
			
			$calls++;
			$original_email = clone $email;
			$email->setBody("<p>&lt;Hello&gt;</p>");
			
			return $email;
		};

		_elgg_services()->hooks->registerHandler('prepare', 'system:email', $handler);

		elgg_send_email(\Elgg\Email::factory([
			'from' => "from@elgg.org",
			'to' => "to@elgg.org",
			'subject' => "Hello",
			'body' => "World",
			'params' => ['foo' => 1],
		]));

		$this->assertEquals(1, $calls);

		$this->assertInstanceOf(\Elgg\Email::class, $original_email);
		$this->assertEquals("to@elgg.org", $original_email->getTo()[0]->getEmail());
		$this->assertEquals("from@elgg.org", $original_email->getFrom()->getEmail());
		$this->assertEquals("Hello", $original_email->getSubject());
		$this->assertEquals("World", $original_email->getBody());
		$this->assertEquals(['foo' => 1], $original_email->getParams());
		
		$message = _elgg_services()->mailer->getLastMessage();

		$this->assertEquals("<Hello>", $message->getBodyText());
	}

	function testElggSendEmailBypass() {
		_elgg_services()->hooks->registerHandler('transport', 'system:email', [\Elgg\Values::class, 'getTrue']);

		$this->assertTrue(elgg_send_email(\Elgg\Email::factory([
			'from' => "from1@elgg.org",
			'to' => "to1@elgg.org",
			'subject' => "Hello",
			'body' => "World",
			'params' => ['foo' => 1],
		])));

		$this->assertNull(_elgg_services()->mailer->getLastMessage());
	}
	
	function testElggSendEmailMultipleRecipients() {
		elgg_send_email(\Elgg\Email::factory([
			'from' => "from@elgg.org",
			'to' => "To <to@elgg.org>",
			'cc' => Address::fromString('cc@elgg.org'),
			'bcc' => ['bcc1@elgg.org', Address::fromString('bcc2@elgg.org')],
			'subject' => 'foo',
			'body' => 'bar',
		]));

		$message = _elgg_services()->mailer->getLastMessage();
		
		$this->assertNotFalse($message->getFrom()->get('from@elgg.org'));
		
		// to
		$this->assertNotFalse($message->getTo()->get('to@elgg.org'));
		$this->assertEquals('To', $message->getTo()->get('to@elgg.org')->getName());
		
		// cc
		$this->assertNotFalse($message->getCc()->get('cc@elgg.org'));
		
		// bcc
		$this->assertNotFalse($message->getBcc()->get('bcc1@elgg.org'));
		$this->assertNotFalse($message->getBcc()->get('bcc2@elgg.org'));
	}

	function testElggEmailSetters() {
		// can't use dataProvider as entities get mangled
		$setterRecipients = function () {
			$address = new Address('no@reply.com');
			$address->setName('No-Reply');
			
			$user = $this->createUser();
			
			return [
				[
					'my@email.com',
					[
						[
							'email' => 'my@email.com',
							'name' => null,
						],
					],
				],
				[
					'Recipient <foo@bar.com>',
					[
						[
							'email' => 'foo@bar.com',
							'name' => 'Recipient',
						],
					],
				],
				[
					$address,
					[
						[
							'email' => $address->getEmail(),
							'name' => $address->getName(),
						],
					],
				],
				[
					$user,
					[
						[
							'email' => $user->email,
							'name' => $user->getDisplayName(),
						],
					],
				],
				[
					[
						$user,
						'my@email.com',
						'Recipient <foo@bar.com>',
						$address,
					],
					[
						[
							'email' => $user->email,
							'name' => $user->getDisplayName(),
						],
						[
							'email' => 'my@email.com',
							'name' => null,
						],
						[
							'email' => 'foo@bar.com',
							'name' => 'Recipient',
						],
						[
							'email' => $address->getEmail(),
							'name' => $address->getName(),
						],
					],
				],
			];
		};
		
		foreach ($setterRecipients() as $args) {
			$recipients = $args[0];
			$expected = $args[1];
			
			foreach (['To', 'Cc', 'Bcc'] as $type) {
				$email = new Email();
				$email->{"set{$type}"}($recipients);
				
				$adresses = $email->{"get{$type}"}();
				$this->assertIsArray($adresses);
				$this->assertCount(count($expected), $adresses);
				
				foreach ($adresses as $index => $adress) {
					$this->assertInstanceOf(Address::class, $adress);
					$this->assertEquals($adress->getEmail(), $expected[$index]['email']);
					$this->assertEquals($adress->getName(), $expected[$index]['name']);
				}
			}
		}
	}
}
