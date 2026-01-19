<?php

namespace Elgg;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Symfony\Component\Mime\Part\AbstractMultipartPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\TextPart;

class EmailServiceIntegrationTest extends IntegrationTestCase {

	public function up() {
		self::createApplication([
			'isolate'=> true,
			'custom_config_values' => ['email_html_part' => true],
		]);
	}

	function testElggSendEmailPassesAllFieldsAsMessageToMailer() {
		$body = str_repeat("<p>You &amp; me &lt; she.</p>\n", 10);
		$body_expected = wordwrap(str_repeat("You & me < she.\n", 10));

		$subject = "<p>You &amp;\r\nme &lt;\rshe.</p>\n\n";
		$subject_expected = "You & me < she.";

		elgg_send_email(\Elgg\Email::factory([
			'from' => "Frōm <from@elgg.org>",
			'to' => "Tō <to@elgg.org>",
			'subject' => $subject,
			'body' => $body,
		]));

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertCount(1, $email->getTo());
		$this->assertEquals('Tō', $email->getTo()[0]->getName());
		$this->assertCount(1, $email->getFrom());
		$this->assertEquals('Frōm', $email->getFrom()[0]->getName());
		$this->assertEquals($subject_expected, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$plain_text_part = null;
		foreach ($email_body->getParts() as $part) {
			if ($part instanceof TextPart && $part->getMediaType() === 'text' && $part->getMediaSubtype() === 'plain') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertInstanceOf(TextPart::class, $plain_text_part);
		
		$this->assertEquals($body_expected, $plain_text_part->getBody());
		$this->assertEquals('utf-8', $email->getTextCharset());
	}

	function testElggSendEmailUsesEvent() {
		$original_email = null;
		$test_handler = $this->registerTestingEvent('prepare', 'system:email', function(\Elgg\Event $event) use (&$original_email) {
			$email = $event->getValue();
			$original_email = clone $email;
			
			$email->setBody("<p>&lt;Hello&gt;</p>");
			
			return $email;
		});

		elgg_send_email([
			'from' => "from@elgg.org",
			'to' => "to@elgg.org",
			'subject' => "Hello",
			'body' => "World",
			'params' => ['foo' => 1],
		]);

		$test_handler->assertNumberOfCalls(1);

		$this->assertInstanceOf(\Elgg\Email::class, $original_email);
		$this->assertEquals("to@elgg.org", $original_email->getTo()[0]->getAddress());
		$this->assertEquals("from@elgg.org", $original_email->getFrom()->getAddress());
		$this->assertEquals("Hello", $original_email->getSubject());
		$this->assertEquals("World", $original_email->getBody());
		$this->assertEquals(['foo' => 1], $original_email->getParams());
		
		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$plain_text_part = null;
		foreach ($email_body->getParts() as $part) {
			if ($part instanceof TextPart && $part->getMediaType() === 'text' && $part->getMediaSubtype() === 'plain') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertInstanceOf(TextPart::class, $plain_text_part);

		$this->assertEquals("<Hello>", $plain_text_part->getBody());
	}

	function testElggSendEmailBypass() {
		_elgg_services()->events->registerHandler('transport', 'system:email', [\Elgg\Values::class, 'getTrue']);

		$this->assertTrue(elgg_send_email([
			'from' => "from1@elgg.org",
			'to' => "to1@elgg.org",
			'subject' => "Hello",
			'body' => "World",
			'params' => ['foo' => 1],
		]));

		$this->assertNull(_elgg_services()->mailer_transport->getLastMessage());
	}
	
	function testElggSendEmailMultipleRecipients() {
		elgg_send_email([
			'from' => "from@elgg.org",
			'to' => "To <to@elgg.org>",
			'cc' => Address::create('cc@elgg.org'),
			'bcc' => ['bcc1@elgg.org', Address::create('bcc2@elgg.org')],
			'subject' => 'foo',
			'body' => 'bar',
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertCount(1, $email->getFrom());
		$this->assertEquals('from@elgg.org', $email->getFrom()[0]->getAddress());
		
		// to
		$this->assertCount(1, $email->getTo());
		$this->assertEquals('To', $email->getTo()[0]->getName());
		
		// cc
		$this->assertCount(1, $email->getCc());
		$this->assertEquals('cc@elgg.org', $email->getCc()[0]->getAddress());
		
		// bcc
		$this->assertCount(2, $email->getBcc());
		$this->assertEquals('bcc1@elgg.org', $email->getBcc()[0]->getAddress());
		$this->assertEquals('bcc1@elgg.org', $email->getBcc()[0]->getAddress());
	}

	function testElggEmailSetters() {
		// can't use dataProvider as entities get mangled
		$setterRecipients = function () {
			$address = new Address('no@reply.com', 'No-Reply');
			
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
							'email' => $address->getAddress(),
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
							'email' => $address->getAddress(),
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
					$this->assertEquals($adress->getAddress(), $expected[$index]['email']);
					$this->assertEquals($adress->getName(), $expected[$index]['name']);
				}
			}
		}
	}
	
	function testPlainTextMessage() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		// no email part
		elgg_set_config('email_html_part', false);
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var TextPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(TextPart::class, $email_body);
	}

	function testPlainTextMessageWithAttachments() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		// no email part
		elgg_set_config('email_html_part', false);
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');

		$this->assertTrue($file->exists());
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'attachments' =>[$file],
			],
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(\Elgg\Email\Attachment::class, $parts[1]);
	}
	
	function testHtmlMessage() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
				
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(TextPart::class, $parts[1]);
		$this->assertEquals('html', $parts[1]->getMediaSubtype());
	}
	
	function testHtmlMessageWithAttachments() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
				
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');

		$this->assertTrue($file->exists());
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'attachments' =>[$file],
			],
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(AlternativePart::class, $parts[0]);
		$this->assertCount(2, $parts[0]->getParts());
		
		$this->assertInstanceOf(\Elgg\Email\Attachment::class, $parts[1]);
	}
	
	function testHtmlMessageFormatting() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		// validate plain text
		$this->assertEquals('Foo link bar', $parts[0]->getBody());
		
		// validate html text
		$html_content = $parts[1]->getBody();
		
		$this->assertStringContainsString('<html', $html_content);
		$this->assertStringContainsString('</html>', $html_content);
		$this->assertStringContainsString('<head>', $html_content);
		$this->assertStringContainsString('</head>', $html_content);
		$this->assertStringContainsString('<body', $html_content);
		$this->assertStringContainsString('</body>', $html_content);
		$this->assertStringContainsString($subject, $html_content);
		$this->assertStringContainsString('http://elgg.org', $html_content);
	}
	
	function testHtmlMessageCustomPart() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => ['html_message' => $body],
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);

		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);

		$this->assertEquals($body, $parts[1]->getBody());
	}
	
	function testHtmlMessageCustomHtmlMessageNoCss() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'html_message' => $body,
				'convert_css' => false
			],
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);

		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertEquals($body, $parts[1]->getBody());
	}
	
	function testHtmlMessageCustomHtmlMessageWithCss() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$expected_body = '<p style="color: red;">Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'html_message' => $body,
				'convert_css' => true,
				'css' => 'p { color: red; }',
			],
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);

		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);

		$this->assertStringContainsString('<html', $parts[1]->getBody());
		$this->assertStringContainsString('</html>', $parts[1]->getBody());
		$this->assertStringContainsString($expected_body, $parts[1]->getBody());
	}
	
	function testHtmlMessageImagesBase64() {
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';
		$body = "<p>Foo <img src='{$image_url}'/> bar</p>";
		$subject = 'subject' . uniqid();
		
		elgg_set_config('email_html_part_images', 'base64');
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		$this->assertEquals($subject, $email->getSubject());

		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$html_content = $parts[1]->getBody();
		$this->assertStringNotContainsString($image_url, $html_content);
		$this->assertStringContainsString('src="data:image/jpeg;charset=UTF-8;base64,', $html_content);
	}
	
	function testHtmlMessageImagesAttachments() {
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';
		$body = "<p>Foo <img src='{$image_url}'/> bar</p>";
		$subject = 'subject' . uniqid();

		elgg_set_config('email_html_part_images', 'attach');
		
		elgg_send_email([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);

		$this->assertEquals($subject, $email->getSubject());
		
		/** @var AbstractMultipartPart $email_body */
		$email_body = $email->getBody();
		$this->assertInstanceOf(AbstractMultipartPart::class, $email_body);
		
		$parts = $email_body->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertNotEmpty($parts[1]->getContentId());
		$this->assertEquals('inline', $parts[1]->getDisposition());
		$this->assertEquals('300x300.jpg', $parts[1]->getFilename());
	}
}
