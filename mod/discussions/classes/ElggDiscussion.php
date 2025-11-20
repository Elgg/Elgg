<?php

/**
 * Discussion topic
 *
 * @property string $status The published status of the discussion (open|closed)
 */
class ElggDiscussion extends ElggObject {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'discussion';
		
		$this->setStatus('open');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		if ($name === 'status') {
			throw new \Elgg\Exceptions\InvalidArgumentException("Use the function 'setStatus()' instead of using the magic setter");
		}
		
		parent::__set($name, $value);
	}
	
	/**
	 * {@inheritdoc}
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
	
	/**
	 * Set the status of the discussion
	 *
	 * @param string $status new status (open|closed)
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\DomainException
	 */
	public function setStatus(string $status): void {
		if (!in_array($status, ['open', 'closed'])) {
			throw new \Elgg\Exceptions\DomainException(__METHOD__ . " doesn't support the status '{$status}' only 'open' and 'closed' are allowed");
		}
		
		if ($this->status === $status) {
			return;
		}
		
		$prev = $this->status;
		$this->setMetadata('status', $status);
		
		if (isset($prev) && $status === 'open') {
			// this will extend the auto closing date after re-opening a discussion
			$this->updateLastAction();
		}
	}
}
