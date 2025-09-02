<?php

namespace Elgg\Notifications;

use Elgg\Views\HtmlFormatter;

/**
 * Mentions notification handler
 *
 * @since 5.0
 */
class MentionsEventHandler extends NonConfigurableNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggEntity || $entity->access_id === ACCESS_PRIVATE) {
			// no actions taken on private content
			return [];
		}
		
		$mentions = $this->getMentions();
		if (empty($mentions)) {
			return [];
		}
		
		// store the usernames as notified
		$notified = (array) $entity->_mentioned_usernames;
		$entity->_mentioned_usernames = array_merge($notified, $mentions);
		
		// transform usernames to users to find notification preferences
		$result = [];
		$users  = elgg_get_entities([
			'type' => 'user',
			'limit' => false,
			'batch' => true,
			'metadata_name_value_pairs' => [
				[
					'name' => 'username',
					'value' => $mentions,
				],
			],
		]);
		/* @var $user \ElggUser */
		foreach ($users as $user) {
			$preference = $user->getNotificationSettings('mentions', true);
			if (empty($preference)) {
				continue;
			}
			
			$result[$user->guid] = $preference;
		}
		
		// remove the actor from the subscribers
		unset($result[$this->event->getActorGUID()]);
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$lan_key = 'notification:mentions:subject';
		if (elgg_language_key_exists("notification:{$this->event->getDescription()}:subject")) {
			$lan_key = "notification:{$this->event->getDescription()}:subject";
		}
		
		return elgg_echo($lan_key, [$this->getEventActor()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		
		$lan_key = 'notification:mentions:body';
		if (elgg_language_key_exists("notification:{$this->event->getDescription()}:body")) {
			$lan_key = "notification:{$this->event->getDescription()}:body";
		}
		
		return elgg_echo($lan_key, [
			$this->getEventActor()?->getDisplayName(),
			$entity?->getDisplayName(),
			$entity?->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addMuteLink(): bool {
		return false;
	}
	
	/**
	 * Get the metadata fields to check for mentions
	 *
	 * @return string[]
	 */
	protected function getMetadataFields(): array {
		return ['description'];
	}
	
	/**
	 * Get usernames which are mentioned in the configured metadata fields
	 *
	 * @return string[]
	 */
	protected function getMentions(): array {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggEntity) {
			return [];
		}
		
		$result = [];
		$metadata_fields = $this->getMetadataFields();
		
		foreach ($metadata_fields as $field) {
			$value = $entity->$field;
			if (empty($value)) {
				continue;
			}
			
			if (!is_array($value)) {
				$value = [$value];
			}
			
			foreach ($value as $text) {
				if (empty($text) || !is_string($text)) {
					continue;
				}
				
				$matches = [];
				preg_match_all(HtmlFormatter::MENTION_REGEX, $text, $matches);
				
				$text_mentions = (array) elgg_extract(3, $matches);
				// remove empty/duplicate values
				$text_mentions = array_values(array_unique(array_filter($text_mentions)));
				// remove trailing punctuation (.) from the username
				$text_mentions = array_map(function($mention) {
					return rtrim($mention, '.');
				}, $text_mentions);
				
				// prepare hook so other plugins can extend the mentions found in the text
				$params = [
					'entity' => $entity,
					'field' => $field,
					'text' => $text,
				];
				$text_mentions = (array) _elgg_services()->events->triggerResults('usernames', 'mentions', $params, $text_mentions);
				
				$result = array_merge($result, $text_mentions);
			}
		}
		
		$result = array_values(array_unique($result));
		
		return $this->filterMentions($result);
	}
	
	/**
	 * Filter mentions
	 * - previous detected mentions
	 *
	 * @param array $mentions the detected mentions from the text
	 *
	 * @return array
	 */
	protected function filterMentions(array $mentions): array {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggEntity) {
			return $mentions;
		}
		
		$already_notified = (array) $entity->_mentioned_usernames;
		
		return array_diff($mentions, $already_notified);
	}
}
