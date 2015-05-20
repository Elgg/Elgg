<?php
namespace Elgg\Mail;

use Zend\Mail\Transport\InMemory as InMemoryTransport;

class MailerTest extends \PHPUnit_Framework_TestCase {

	public $hookArgs = [];

	/**
	 * @var InMemoryTransport
	 */
	public $mailer;

	public function setUp() {
		$this->mailer = new InMemoryTransport();
		_elgg_services()->setValue('mailer', $this->mailer);
	}

	public function tearDown() {
		// don't keep messages in memory for other test cases
		$this->setUp();
	}

	function testElggSendEmailPassesAllFieldsAsMessageToMailer() {
		$body = str_repeat("<p>You &amp; me &lt; she.</p>\n", 10);
		$body_expected = wordwrap(str_repeat("You & me < she.\n", 10));

		$subject = "<p>You &amp;\r\nme &lt;\rshe.</p>\n\n";
		$subject_expected = "You & me < she.";

		elgg_send_email("Frōm <from@elgg.org>", "Tō <to@elgg.org>", $subject, $body);
		
		$message = $this->mailer->getLastMessage();
		
		$this->assertEquals('Tō', $message->getTo()->get('to@elgg.org')->getName());
		$this->assertEquals('Frōm', $message->getFrom()->get('from@elgg.org')->getName());
		$this->assertEquals($subject_expected, $message->getSubject());
		$this->assertEquals($body_expected, $message->getBodyText());
		$this->assertEquals('UTF-8', $message->getEncoding());
	}

	function testElggSendEmailUsesHook() {
		_elgg_services()->hooks->registerHandler('email', 'system', [$this, 'handleEmailHook1']);

		elgg_send_email("from@elgg.org", "to@elgg.org", "Hello", "World", ['foo' => 1]);

		_elgg_services()->hooks->unregisterHandler('email', 'system', [$this, 'handleEmailHook1']);

		$expected_data = [
			'to' => "to@elgg.org",
			'from' => "from@elgg.org",
			'subject' => "Hello",
			'body' => "World",
			'headers' => [
				"Content-Type" => "text/plain; charset=UTF-8; format=flowed",
				"MIME-Version" => "1.0",
				"Content-Transfer-Encoding" => "8bit",
			],
			'params' => ['foo' => 1],
		];
		$this->assertEquals($expected_data, $this->hookArgs[0][2]);
		$this->assertEquals($expected_data, $this->hookArgs[0][3]);

		$message = $this->mailer->getLastMessage();

		$this->assertEquals("<Hello>", $message->getBodyText());
	}

	function testElggSendEmailBypass() {
		_elgg_services()->hooks->registerHandler('email', 'system', [$this, 'handleEmailHookTrue']);

		$this->assertTrue(elgg_send_email("from@elgg.org", "to@elgg.org", "Hello", "World", ['foo' => 1]));

		_elgg_services()->hooks->unregisterHandler('email', 'system', [$this, 'handleEmailHookTrue']);

		$this->assertNull($this->mailer->getLastMessage());
	}

	function handleEmailHook1($hook, $type, $value, $params) {
		$this->hookArgs[] = func_get_args();
		$value['body'] = "<p>&lt;Hello&gt;</p>";
		return $value;
	}

	function handleEmailHookTrue() {
		return true;
	}
}
