<?php

namespace Elgg\Messages;

use Elgg\IntegrationTestCase;
use Zend\Mail\Message;

/**
 * @group MessagesPlugin
 */
class MessagesPluginTest extends IntegrationTestCase {

	public function up() {
		
	}

	public function down() {

	}

	public function testCanSendMessage() {

		$sender = $this->createUser();
		$recipient = $this->createUser();
		$recipient->setNotificationSetting('email', true);

		$subject = 'Message Subject';
		$body = 'Message Body';

		$sent_guid = messages_send($subject, $body, $recipient->guid, $sender->guid, 0, true);

		$this->assertNotFalse($sent_guid);

		elgg_set_ignore_access(true);

		$count = messages_count_unread($recipient->guid);
		$this->assertEquals(1, $count);

		$count = messages_count_unread($sender->guid);
		$this->assertEquals(0, $count);

		/* @var $message \ElggMessage */
		$message = get_entity($sent_guid);
		$this->assertInstanceOf(\ElggMessage::class, $message);

		$this->assertEquals($subject, $message->title);
		$this->assertEquals($body, $message->description);
		
		$this->assertEquals($recipient->guid, $message->toId);
		$this->assertEquals($recipient, $message->getRecipient());
		
		$this->assertEquals($sender->guid, $message->fromId);
		$this->assertEquals($sender, $message->getSender());

		elgg_set_ignore_access(false);

		$this->assertTrue(has_access_to_entity($message, $recipient));
		$this->assertFalse(has_access_to_entity($message, $sender));

		$notification = _elgg_services()->mailer->getLastMessage();
		/* @var $notification \Zend\Mail\Message */

		$this->assertInstanceOf(Message::class, $notification);

		$expected_subject = elgg_echo('messages:email:subject', [], $recipient->language);
		$expected_body = elgg_echo('messages:email:body', [
			$sender->getDisplayName(),
			$body,
			elgg_generate_url('collection:object:messages:owner', [
				'username' => $recipient->username,
			]),
			$sender->getDisplayName(),
			elgg_generate_url('add:object:messages', [
				'send_to' => $sender->guid,
			]),
		],
			$recipient->language
		);

		$notification_subject = $notification->getSubject();
		$notification_body = $notification->getBodyText();

		$this->assertEquals($expected_subject, $notification_subject);
		$this->assertEquals($expected_body, $notification_body);

	}
}
