<?php

namespace Elgg\Messages;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Zend\Mail\Message;

/**
 * @group Actions
 * @group MessagesPlugin
 */
class SendActionTest extends ActionResponseTestCase {

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
		$this->assertEquals('messages/compose', $response->getForwardURL());

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
		$this->assertEquals('messages/compose', $response->getForwardURL());

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
		$this->assertEquals('messages/compose', $response->getForwardURL());

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
		$this->assertEquals('messages/compose', $response->getForwardURL());

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
		$this->assertEquals('messages/compose', $response->getForwardURL());

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

		$messages = _elgg_services()->systemMessages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('messages:posted', [], $user->language), array_shift($messages['success']));
		$this->assertEquals(	'messages/inbox/' . $user->username, $response->getForwardURL());

		elgg_set_ignore_access(true);

		$data= $response->getContent();
		$message = get_entity($data['sent_guid']);

		$this->assertInstanceOf(\ElggObject::class, $message);
		$this->assertEquals('messages', $message->getSubtype());
		$this->assertEquals('Message Subject', $message->title);
		$this->assertEquals('Message Body', $message->description);

		elgg_set_ignore_access(false);

		$notification = _elgg_services()->mailer->getLastMessage();
		/* @var $notification \Zend\Mail\Message */

		$this->assertInstanceOf(Message::class, $notification);

		$expected_subject = elgg_echo('messages:email:subject', [], $recipient->language);
		$expected_body = elgg_echo('messages:email:body', [
			$user->name,
			'Message Body',
			elgg_get_site_url() . "messages/inbox/" . $recipient->username,
			$user->name,
			elgg_get_site_url() . "messages/compose?send_to=" . $user->guid,
		],
			$recipient->language
		);

		$notification_subject = $notification->getSubject();
		$notification_body = $notification->getBodyText();

		$this->assertEquals($expected_subject, $notification_subject);
		$this->assertEquals($expected_body, $notification_body);

		_elgg_services()->session->removeLoggedInUser();
	}
}