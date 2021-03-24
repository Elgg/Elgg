<?php

namespace Elgg;

use Laminas\Mail\Transport\InMemory as InMemoryTransport;
use Elgg\Email\Address;
use Laminas\Mime\Mime;
use Laminas\Mail\Header\ContentType;
use Elgg\Email\HtmlPart;

class EmailServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	public $hookArgs = [];

	/**
	 * @var InMemoryTransport
	 */
	public $mailer;

	public function up() {
		self::createApplication([
			'isolate'=> true,
			'custom_config_values' => ['email_html_part' => true],
		]);
	}

	public function down() {
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

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		
		$this->assertEquals('Tō', $message->getTo()->get('to@elgg.org')->getName());
		$this->assertEquals('Frōm', $message->getFrom()->get('from@elgg.org')->getName());
		$this->assertEquals($subject_expected, $message->getSubject());
		
		$plain_text_part = null;
		foreach ($message->getBody()->getParts() as $part) {
			if ($part->getId() === 'plaintext') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertNotEmpty($plain_text_part);
		
		$this->assertEquals($body_expected, $plain_text_part->getRawContent());
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
		
		$plain_text_part = null;
		foreach ($message->getBody()->getParts() as $part) {
			if ($part->getId() === 'plaintext') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertNotEmpty($plain_text_part);

		$this->assertEquals("<Hello>", $plain_text_part->getRawContent());
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
	
	function testPlainTextMessage() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		// no email part
		elgg_set_config('email_html_part', false);
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();
		$this->assertCount(1, $parts);
		
		$this->assertInstanceOf(\Elgg\Email\PlainTextPart::class, $parts[0]);
		$content_type = $message->getHeaders()->get('content-type');
		$this->assertInstanceOf(ContentType::class, $content_type);
		$this->assertEquals(Mime::TYPE_TEXT, $content_type->getType());
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
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'attachments' =>[$file],
			],
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(\Elgg\Email\PlainTextPart::class, $parts[0]);
		$this->assertInstanceOf(\Elgg\Email\Attachment::class, $parts[1]);
		
		$content_type = $message->getHeaders()->get('content-type');
		$this->assertInstanceOf(ContentType::class, $content_type);
		$this->assertEquals(Mime::MULTIPART_MIXED, $content_type->getType());
	}
	
	function testHtmlMessage() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
				
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(\Elgg\Email\PlainTextPart::class, $parts[0]);
		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);
		
		$content_type = $message->getHeaders()->get('content-type');
		$this->assertInstanceOf(ContentType::class, $content_type);
		$this->assertEquals(Mime::MULTIPART_ALTERNATIVE, $content_type->getType());
	}
	
	function testHtmlMessageWithAttachments() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
				
		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('foobar.txt');

		$this->assertTrue($file->exists());
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'attachments' =>[$file],
			],
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(\Laminas\Mime\Part::class, $parts[0]);
		$this->assertInstanceOf(\Elgg\Email\Attachment::class, $parts[1]);
		
		$this->assertEquals(Mime::MULTIPART_ALTERNATIVE, $parts[0]->getType());
		
		$content_type = $message->getHeaders()->get('content-type');
		$this->assertInstanceOf(ContentType::class, $content_type);
		$this->assertEquals(Mime::MULTIPART_MIXED, $content_type->getType());
	}
	
	function testHtmlMessageFormatting() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();
		$this->assertCount(2, $parts);
		
		$this->assertInstanceOf(\Elgg\Email\PlainTextPart::class, $parts[0]);
		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);
		
		// validate plain text
		$this->assertEquals('Foo link bar', $parts[0]->getRawContent());
		
		// validate html text
		$html_content = $parts[1]->getRawContent();
		
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
		
		$custom_part = new HtmlPart($body);
		$custom_part->setId('custom_html');
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => ['html_message' => $custom_part],
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();

		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);

		$this->assertEquals($body, $parts[1]->getRawContent());
		$this->assertEquals('custom_html', $parts[1]->getId());
	}
	
	function testHtmlMessageCustomHtmlMessageNoCss() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'html_message' => $body,
				'convert_css' => false
			],
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();

		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);

		$this->assertEquals($body, $parts[1]->getRawContent());
	}
	
	function testHtmlMessageCustomHtmlMessageWithCss() {
		$body = '<p>Foo <a href="http://elgg.org">link</a> bar</p>';
		$expected_body = '<p style="color: red;">Foo <a href="http://elgg.org">link</a> bar</p>';
		$subject = 'subject' . uniqid();
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
			'params' => [
				'html_message' => $body,
				'convert_css' => true,
				'css' => 'p { color: red; }',
			],
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());
		
		$parts = $message->getBody()->getParts();

		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);

		$this->assertStringContainsString('<html', $parts[1]->getRawContent());
		$this->assertStringContainsString('</html>', $parts[1]->getRawContent());
		$this->assertStringContainsString($expected_body, $parts[1]->getRawContent());
	}
	
	function testHtmlMessageImagesBase64() {
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';
		$body = "<p>Foo <img src='{$image_url}'/> bar</p>";
		$subject = 'subject' . uniqid();
		
		elgg_set_config('email_html_part_images', 'base64');
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());

		$parts = $message->getBody()->getParts();
		
		$this->assertInstanceOf(\Elgg\Email\HtmlPart::class, $parts[1]);
		
		$html_content = $parts[1]->getRawContent();
		$this->assertStringNotContainsString($image_url, $html_content);
		$this->assertStringContainsString('src="data:image/jpeg;charset=UTF-8;base64,', $html_content);
	}
	
	function testHtmlMessageImagesAttachments() {
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';
		$body = "<p>Foo <img src='{$image_url}'/> bar</p>";
		$subject = 'subject' . uniqid();

		elgg_set_config('email_html_part_images', 'attach');
		
		elgg_send_email(\Elgg\Email::factory([
			'from' => 'from@elgg.org',
			'to' => 'to@elgg.org',
			'subject' => $subject,
			'body' => $body,
		]));

		/* @var $message \Laminas\Mail\Message */
		$message = _elgg_services()->mailer->getLastMessage();
		$this->assertEquals($subject, $message->getSubject());

		$parts = $message->getBody()->getParts();
		
		$this->assertInstanceOf(\Laminas\Mime\Part::class, $parts[1]);
		
		$msg_content = $parts[1]->getRawContent();
		$this->assertStringContainsString('Content-ID: <htmltext>', $msg_content);
		$this->assertStringContainsString('Content-Disposition: inline; filename="300x300.jpg"', $msg_content);
	}
}
