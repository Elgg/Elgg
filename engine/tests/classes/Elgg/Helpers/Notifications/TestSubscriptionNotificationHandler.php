<?php

namespace Elgg\Helpers\Notifications;

use Elgg\Notifications\NotificationEventHandler;
use Elgg\Project\Paths;

class TestSubscriptionNotificationHandler extends NotificationEventHandler {
	
	public function getSubscriptions(): array {
		$actor = $this->event->getActor();
		
		return [
			$actor->guid => ['test_method'],
		];
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
}
