<?php

namespace Elgg\Messages;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Laminas\Mail\Message;

/**
 * @group Actions
 * @group MessagesPlugin
 */
class SendActionTest extends ActionResponseTestCase {

	public function up() {
		self::createApplication(['isolate'=> true]);
		$this->startPlugin();
		
		elgg_register_plugin_hook_handler('permissions_check', 'object', 'Elgg\Messages\Permissions::canEdit');
		elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'Elgg\Messages\Permissions::canEditContainer');
		
		parent::up();
	}

	public function testSendFailsWithoutRecipient() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:blank'), $response->getContent());
		$this->assertEquals('messages/add', $response->getForwardURL());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSendFailsToSelf() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipients' => $user->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:self'), $response->getContent());
		$this->assertEquals('messages/add', $response->getForwardURL());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSendFailsToInvalidUser() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipients' => 'abc',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:nonexist'), $response->getContent());
		$this->assertEquals('messages/add', $response->getForwardURL());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSendFailsWithoutMessageSubject() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'body' => 'Message Body',
			'recipients' => $recipient->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:blank'), $response->getContent());
		$this->assertEquals('messages/add', $response->getForwardURL());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSendFailsWithoutMessageBody() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'recipients' => $recipient->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:blank'), $response->getContent());
		$this->assertEquals('messages/add', $response->getForwardURL());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSendSuccess() {

		$user = $this->createUser([], [
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		$recipient->setNotificationSetting('email', true);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipients' => $recipient->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('messages:posted', [], $user->language));
		$this->assertEquals('messages/inbox/' . $user->username, $response->getForwardURL());

		elgg_call(ELGG_IGNORE_ACCESS, function () use ($response) {
			$data = $response->getContent();
			$message = get_entity($data['sent_guid']);
	
			$this->assertInstanceOf(\ElggObject::class, $message);
			$this->assertEquals('messages', $message->getSubtype());
			$this->assertEquals('Message Subject', $message->title);
			$this->assertEquals('Message Body', $message->description);
		});
		
		$notification = _elgg_services()->mailer->getLastMessage();
		/* @var $notification \Laminas\Mail\Message */

		$this->assertInstanceOf(Message::class, $notification);

		$expected_subject = elgg_echo('messages:email:subject', [], $recipient->language);
		$expected_body = elgg_echo('messages:email:body', [
			$user->getDisplayName(),
			'Message Body',
			elgg_generate_url('collection:object:messages:owner', [
				'username' => $recipient->username,
			]),
			$user->getDisplayName(),
			elgg_generate_url('add:object:messages', [
				'send_to' => $user->guid,
			]),
		],
			$recipient->language
		);

		$plain_text_part = null;
		foreach ($notification->getBody()->getParts() as $part) {
			if ($part->getId() === 'plaintext') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertNotEmpty($plain_text_part);

		$this->assertEquals($expected_subject, $notification->getSubject());
		$this->assertStringContainsString(preg_replace('/\\n/m', ' ', $expected_body), preg_replace('/\\n/m', ' ', $plain_text_part->getRawContent()));

		_elgg_services()->session->removeLoggedInUser();
	}
}