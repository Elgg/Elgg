<?php

namespace Elgg\Notifications;

use Elgg\Database\QueryBuilder;

/**
 * Notification Event Handler for 'user' 'user' 'remove_admin' action
 *
 * @since 4.0
 */
class RemoveAdminUserEventHandler extends NotificationEventHandler {
	
	/**
	 * Tells if the recipient is the user being changed
	 *
	 * @return bool
	 */
	protected function recipientIsChangedUser(\ElggUser $recipient): bool {
		return $this->event->getObject()->guid === $recipient->guid;
	}
		
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsChangedUser($recipient)) {
			return elgg_echo('admin:notification:remove_admin:user:subject', [elgg_get_site_entity()->getDisplayName()], $recipient->getLanguage());
		} else {
			return elgg_echo('admin:notification:remove_admin:admin:subject', [elgg_get_site_entity()->getDisplayName()], $recipient->getLanguage());
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		
		$actor = $this->event->getActor();
		if (!$actor instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		$site = elgg_get_site_entity();
		
		if ($this->recipientIsChangedUser($recipient)) {
			return elgg_echo('admin:notification:remove_admin:user:body', [
				$actor->getDisplayName(),
				$site->getDisplayName(),
				$site->getURL(),
			], $recipient->getLanguage());
		} else {
			$entity = $this->event->getObject();
			
			return elgg_echo('admin:notification:remove_admin:admin:body', [
				$actor->getDisplayName(),
				$entity->getDisplayName(),
				$site->getDisplayName(),
				$entity->getURL(),
			], $recipient->getLanguage());
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		if ($this->recipientIsChangedUser($recipient)) {
			return '';
		}
		
		return elgg_generate_url('admin', ['segments' => 'users/admins']);
	}
	
	/**
	 * Add the user to the subscribers when changing admin rights
	 *
	 * {@inheritDoc}
	 */
	public function getSubscriptions(): array {
		$result = parent::getSubscriptions();
		
		/* @var $user \ElggUser */
		$user = $this->event->getObject();
		
		if (_elgg_services()->config->security_notify_user_admin) {
			// add the user to the subscribers
			$result[$user->guid] = ['email'];
		}
		
		if (_elgg_services()->config->security_notify_admins) {
			// add the current site admins to the subscribers

			$admin_batch = elgg_get_admins([
				'limit' => false,
				'wheres' => [
					function (QueryBuilder $qb, $main_alias) use ($user) {
						return $qb->compare("{$main_alias}.guid", '!=', $user->guid, ELGG_VALUE_GUID);
					},
				],
				'batch' => true,
			]);

			foreach ($admin_batch as $admin) {
				$result[$admin->guid] = ['email'];
			}
		}
		
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
}
