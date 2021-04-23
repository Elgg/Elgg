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
	public function hasSubscriptions(int $user_guid = 0, $methods = []): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		if (parent::hasSubscriptions($user_guid, $methods)) {
			return true;
		}
		
		// the subscribers for discussions are extended with the group memberships also check there
		// @see \Elgg\Discussions\Notifications::addGroupSubscribersToCommentOnDiscussionSubscriptions()
		return _elgg_services()->subscriptions->hasSubscriptions($user_guid, $this->container_guid, $methods);
	}
}
