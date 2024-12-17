<?php

/**
 * Discussion topic
 *
 * @property string $status The published status of the discussion (open|closed)
 */
class ElggDiscussion extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'discussion';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function hasSubscriptions(int $user_guid = 0, string|array $methods = []): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session_manager->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		if (parent::hasSubscriptions($user_guid, $methods)) {
			return true;
		}
		
		// the subscribers for discussions are extended with the group memberships also check there
		// @see \Elgg\Discussions\Notifications::addGroupSubscribersToCommentOnDiscussionSubscriptions()
		return _elgg_services()->subscriptions->hasSubscriptions($user_guid, $this->container_guid, $methods);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
			'required' => true,
		];
		
		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('discussion:topic:description'),
			'name' => 'description',
			'required' => true,
			'editor_type' => 'simple',
		];
		
		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('tags'),
			'name' => 'tags',
		];
		
		$result[] = [
			'#type' => 'select',
			'#label' => elgg_echo('discussion:topic:status'),
			'name' => 'status',
			'options_values' => [
				'open' => elgg_echo('status:open'),
				'closed' => elgg_echo('status:closed'),
			],
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access'),
			'#class' => 'discussion-access',
			'name' => 'access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'discussion',
		];
		
		return $result;
	}
}
