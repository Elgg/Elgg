<?php

namespace Elgg\Likes\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Send a notification to the Entity owner when a Likes annotation is created
 *
 * @since 6.1
 */
class CreateLikesEventHandler extends NotificationEventHandler {
	
	/**
	 * Get the Likes annotation
	 *
	 * @return null|\ElggAnnotation
	 */
	protected function getLikesAnnotation(): ?\ElggAnnotation {
		$annotation = $this->event->getObject();
		if (!$annotation instanceof \ElggAnnotation) {
			return null;
		}
		
		return $annotation->name === 'likes' ? $annotation : null;
	}
	
	/**
	 * Get the liked entity
	 *
	 * @return null|\ElggEntity
	 */
	protected function getLikedEntity(): ?\ElggEntity {
		return $this->getLikesAnnotation()?->getEntity();
	}
	
	/**
	 * Get a title string from the liked entity
	 *
	 * @param int|null $max_length max length of the title
	 *
	 * @return string
	 */
	protected function getEntityTitle(?int $max_length = null): string {
		$entity = $this->getLikedEntity();
		if (!$entity instanceof \ElggEntity) {
			return '';
		}
		
		$title = $entity->getDisplayName();
		if (elgg_is_empty($title)) {
			$title = elgg_get_excerpt((string) $entity->description);
		}
		
		if (isset($max_length)) {
			return elgg_get_excerpt($title, $max_length);
		}
		
		return $title;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$owner = $this->getLikedEntity()?->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return [];
		}
		
		if ($owner->guid === $this->event->getActorGUID()) {
			return [];
		}
		
		return [
			$owner->guid => array_keys(array_filter($owner->getNotificationSettings())),
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('likes:notifications:subject', [
			$this->getEventActor()?->getDisplayName(),
			$this->getEntityTitle(80),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('likes:notifications:subject', [
			$this->getEventActor()?->getDisplayName(),
			$this->getEntityTitle(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		return elgg_echo('likes:notifications:body', [
			$this->getEventActor()?->getDisplayName(),
			$this->getEntityTitle(),
			elgg_get_site_entity()->getDisplayName(),
			$this->getLikedEntity()?->getURL(),
			$this->getEventActor()?->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		return (string) $this->getLikedEntity()?->getURL();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
}
