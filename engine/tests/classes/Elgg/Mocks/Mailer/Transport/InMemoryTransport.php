<?php

namespace Elgg\Mocks\Mailer\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;

class InMemoryTransport extends AbstractTransport {
	
	protected ?SentMessage $last_message = null;
	
	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string {
		return 'null://';
	}
	
	protected function doSend(SentMessage $message): void {
		$this->last_message = $message;
	}
	
	/**
	 * Get the last message sent
	 *
	 * @return SentMessage|null
	 */
	public function getLastMessage(): ?SentMessage {
		return $this->last_message;
	}
	
	/**
	 * Based of the last message get the last email source message
	 *
	 * @return Email|null
	 */
	public function getLastEmail(): ?Email {
		if (!isset($this->last_message)) {
			return null;
		}
		
		$original = $this->last_message->getOriginalMessage();
		
		return $original instanceof Email ? $original : null;
	}
}
