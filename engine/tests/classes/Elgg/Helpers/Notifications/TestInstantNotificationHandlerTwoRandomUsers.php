<?php

namespace Elgg\Helpers\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;
use Elgg\Project\Paths;
use Elgg\TestSeeding;
use Psr\Log\LogLevel;

class TestInstantNotificationHandlerTwoRandomUsers extends InstantNotificationEventHandler {
	
	use TestSeeding {
		log as logSeeding;
	}
	
	protected static array $user_guids = [];
	
	public function __destruct() {
		$this->clearSeeds();
	}
	
	public function getSubscriptions(): array {
		$result = [];
		
		$user = $this->getRandomUser(static::$user_guids);
		static::$user_guids[] = $user->guid;
		$result[$user->guid] = ['test_method'];
		
		$user = $this->getRandomUser(static::$user_guids);
		static::$user_guids[] = $user->guid;
		$result[$user->guid] = ['test_method'];
		
		return $result;
	}
	
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return __METHOD__ . ' ' . $recipient->getDisplayName();
	}

	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return __METHOD__ . ' ' . $recipient->getDisplayName();
	}

	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		return __METHOD__ . ' ' . $recipient->getDisplayName();
	}

	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		return __METHOD__ . ' ' . $recipient->getDisplayName();
	}

	protected function getNotificationAttachments(\ElggUser $recipient, string $method): array {
		return [
			[
				'filepath' => Paths::elgg() . 'README.md',
				'filename' => 'README.md',
				'type' => 'text/markdown',
			],
		];
	}
	
	/**
	 * Log a message
	 *
	 * @param string $level   Severity
	 * @param mixed  $message Message
	 * @param array  $context Context
	 *
	 * @return bool
	 */
	public function log($level, $message = null, array $context = []) {
		if (!isset($message)) {
			$message = $level;
			$level = LogLevel::NOTICE;
		}
		
		$this->logSeeding($message, $level);
	}
}
