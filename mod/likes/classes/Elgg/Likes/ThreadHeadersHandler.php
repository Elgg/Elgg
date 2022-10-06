<?php

namespace Elgg\Likes;

/**
 * Sets the thread headers for emails
 *
 * @since 4.2
 */
class ThreadHeadersHandler {
	
	/**
	 * Adds default thread SMTP headers to group messages correctly.
	 * Note that it won't be sufficient for some email clients. Ie. Gmail is looking at message subject anyway.
	 *
	 * @param \Elgg\Event $event 'prepare', 'system:email'
	 *
	 * @return void|\Elgg\Email
	 */
	public function __invoke(\Elgg\Event $event) {
		$email = $event->getValue();
		if (!$email instanceof \Elgg\Email) {
			return;
		}
		
		$params = $email->getParams();
		
		$annotation = elgg_extract('object', $params);
		if (!$annotation instanceof \ElggAnnotation || $annotation->name !== 'likes') {
			return;
		}
		
		$entity = $annotation->getEntity();
		if (!$entity instanceof \ElggEntity) {
			// how did we get here?
			return;
		}
		
		$thread_message_id = "<{$email->createEntityMessageID($entity)}>";
		
		$email->addHeader('In-Reply-To', $thread_message_id);
		$email->addHeader('References', $thread_message_id);
		
		return $email;
	}
}
