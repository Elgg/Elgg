<?php

namespace Elgg\Mail;

use Zend\Mail\Transport\InMemory as InMemoryTransport;

/**
 * @group EmailService
 * @group UnitTests
 */
class EmailServiceUnitTest extends \Elgg\UnitTestCase {

	public $hookArgs = [];

	/**
	 * @var InMemoryTransport
	 */
	public $mailer;

	public function up() {
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->hooks->restore();
	}

	function testElggSendEmailPassesAllFieldsAsMessageToMailer() {
		$body = str_repeat("<p>You &amp; me &lt; she.</p>\n", 10);
		$body_expected = trim(wordwrap(str_repeat("You & me < she.\n", 10)));

		$subject = "<p>You &amp;\r\nme &lt;\rshe.</p>\n\n";
		$subject_expected = "You & me < she.";

		elgg_send_email("Frōm <from@elgg.org>", "Tō <to@elgg.org>", $subject, $body);

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
		$handler = function ($hook, $type, $email, $params) use (&$original_email, &$calls) {
			$calls++;
			$original_email = clone $email;
			$email->setBody("<p>&lt;Hello&gt;</p>");
			return $email;
		};

		_elgg_services()->hooks->registerHandler('prepare', 'system:email', $handler);

		elgg_send_email("from@elgg.org", "to@elgg.org", "Hello", "World", ['foo' => 1]);

		$this->assertEquals(1, $calls);

		$this->assertInstanceOf(\Elgg\Email::class, $original_email);
		$this->assertEquals("to@elgg.org", $original_email->getTo()->getEmail());
		$this->assertEquals("from@elgg.org", $original_email->getFrom()->getEmail());
		$this->assertEquals("Hello", $original_email->getSubject());
		$this->assertEquals("World", $original_email->getBody());
		$this->assertEquals(['foo' => 1], $original_email->getParams());
		
		$message = _elgg_services()->mailer->getLastMessage();

		$this->assertEquals("<Hello>", $message->getBodyText());
	}

	function testElggSendEmailBypass() {
		_elgg_services()->hooks->registerHandler('transport', 'system:email', [\Elgg\Values::class, 'getTrue']);

		$this->assertTrue(elgg_send_email("from@elgg.org", "to@elgg.org", "Hello", "World", ['foo' => 1]));

		$this->assertNull(_elgg_services()->mailer->getLastMessage());
	}
}
