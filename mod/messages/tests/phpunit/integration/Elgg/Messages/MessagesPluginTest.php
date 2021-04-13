<?php

namespace Elgg\Messages;

use Elgg\IntegrationTestCase;
use Laminas\Mail\Message;

/**
 * @group MessagesPlugin
 */
class MessagesPluginTest extends IntegrationTestCase {

	public function up() {
		self::createApplication(['isolate' => true]);
		
		elgg_register_plugin_hook_handler('permissions_check', 'object', 'Elgg\Messages\Permissions::canEdit');
		elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'Elgg\Messages\Permissions::canEditContainer');
	}

	public function down() {

	}

	public function testCanSendMessage() {

		$sender = $this->createUser();
		$recipient = $this->createUser();
		$recipient->setNotificationSetting('email', true);

		$subject = 'Message Subject';
		$body = 'Message Body';

		$message = null;
		
		$sent_guid = messages_send($subject, $body, $recipient->guid, $sender->guid, 0, true);

		$this->assertNotFalse($sent_guid);

		elgg_call(ELGG_IGNORE_ACCESS, function () use ($recipient, $sender, $sent_guid, $subject, $body, &$message) {
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
		});
		
		$this->assertTrue(has_access_to_entity($message, $recipient));
		$this->assertFalse(has_access_to_entity($message, $sender));

		$notification = _elgg_services()->mailer->getLastMessage();
		/* @var $notification \Laminas\Mail\Message */

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

		$plain_text_part = null;
		foreach ($notification->getBody()->getParts() as $part) {
			if ($part->getId() === 'plaintext') {
				$plain_text_part = $part;
				break;
			}
		}
		
		$this->assertNotEmpty($plain_text_part);

		$this->assertEquals($expected_subject, $notification->getSubject());
		$this->assertStringContainsString($expected_body, $plain_text_part->getRawContent());

	}
}
