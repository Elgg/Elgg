<?php

namespace Elgg\Messages;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Symfony\Component\Mime\Part\TextPart;

class SendActionTest extends ActionResponseTestCase {

	public function up() {
		parent::up();
		
		$this->startPlugin();
	}

	public function testSendFailsWithoutRecipient() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:blank'), $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());
	}

	public function testSendFailsToSelf() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipient' => $user->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:self'), $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());
	}

	public function testSendFailsToInvalidUser() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipient' => '-1',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:user:nonexist'), $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());
	}

	public function testSendFailsWithoutMessageSubject() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'body' => 'Message Body',
			'recipient' => $recipient->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:blank'), $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());
	}

	public function testSendFailsWithoutMessageBody() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'recipient' => $recipient->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('messages:blank'), $response->getContent());
		$this->assertEquals(REFERRER, $response->getForwardURL());
	}

	public function testSendSuccess() {
		$user = $this->createUser([
			'language' => 'de',
		]);

		$recipient = $this->createUser([
			'language' => 'es',
		]);

		$recipient->setNotificationSetting('email', true);

		_elgg_services()->session_manager->setLoggedInUser($user);

		$response = $this->executeAction('messages/send', [
			'subject' => 'Message Subject',
			'body' => 'Message Body',
			'recipient' => $recipient->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('messages:posted', [], $user->getLanguage()));
		$this->assertEquals(elgg_generate_url('collection:object:messages:owner', ['username' => $user->username]), $response->getForwardURL());

		elgg_call(ELGG_IGNORE_ACCESS, function () use ($response) {
			$data = $response->getContent();
			$message = get_entity($data['sent_guid']);
	
			$this->assertInstanceOf(\ElggObject::class, $message);
			$this->assertEquals('messages', $message->getSubtype());
			$this->assertEquals('Message Subject', $message->title);
			$this->assertEquals('Message Body', $message->description);
		});
		
		$expected_subject = elgg_echo('messages:email:subject', [], $recipient->getLanguage());
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
			$recipient->getLanguage()
		);

		/** @var SymfonyEmail $email */
		$email = _elgg_services()->mailer_transport->getLastEmail();
		$this->assertInstanceOf(SymfonyEmail::class, $email);
		
		/** @var TextPart $plain_text_part */
		$plain_text_part = $email->getBody();
		$this->assertInstanceOf(TextPart::class, $plain_text_part);

		$this->assertEquals($expected_subject, $email->getSubject());
		$this->assertStringContainsString($expected_body, $plain_text_part->getBody());
	}
}
