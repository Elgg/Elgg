<?php

namespace Elgg\Traits\Entity;

/**
 * ElggEntity functions to manage subscriptions
 *
 * @since 4.0
 */
trait Subscriptions {
	
	/**
	 * Add a subscription between a user and the current entity
	 *
	 * @param int             $user_guid the user to subscribe (default: current user)
	 * @param string|string[] $methods   notification method (default: current registered methods)
	 * @param string          $type      entity type
	 * @param string          $subtype   entity subtype
	 * @param string          $action    notification action (eg. 'create')
	 *
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	public function addSubscription(int $user_guid = 0, $methods = [], string $type = null, string $subtype = null, string $action = null): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		$result = true;
		foreach ($methods as $method) {
			$result &= _elgg_services()->subscriptions->addSubscription($user_guid, $method, $this->guid, $type, $subtype, $action);
		}
		
		return $result;
	}
	
	/**
	 * Is there a subscription between a user and the current entity
	 *
	 * @param int             $user_guid the user to subscribe (default: current user)
	 * @param string|string[] $methods   notification method (default: current registered methods)
	 * @param string          $type      entity type
	 * @param string          $subtype   entity subtype
	 * @param string          $action    notification action (eg. 'create')
	 *
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	public function hasSubscription(int $user_guid = 0, $methods = [], string $type = null, string $subtype = null, string $action = null): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		$result = true;
		foreach ($methods as $method) {
			$result &= _elgg_services()->subscriptions->hasSubscription($user_guid, $method, $this->guid, $type, $subtype, $action);
		}
		
		return $result;
	}
	
	/**
	 * Is there any subscriptions between the user and the current entity
	 *
	 * @param int             $user_guid the user to subscribe (default: current user)
	 * @param string|string[] $methods   notification method (default: current registered methods)
	 *
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	public function hasSubscriptions(int $user_guid = 0, $methods = []): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		return _elgg_services()->subscriptions->hasSubscriptions($user_guid, $this->guid, $methods);
	}
	
	/**
	 * Remove a subscription between a user and the current entity
	 *
	 * @param int             $user_guid the user to subscribe (default: current user)
	 * @param string|string[] $methods   notification method (default: current registered methods)
	 * @param string          $type      entity type
	 * @param string          $subtype   entity subtype
	 * @param string          $action    notification action (eg. 'create')
	 *
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	public function removeSubscription(int $user_guid = 0, $methods = [], string $type = null, string $subtype = null, string $action = null): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = $this->normalizeSubscriptionMethods($methods);
		
		$result = true;
		foreach ($methods as $method) {
			$result &= _elgg_services()->subscriptions->removeSubscription($user_guid, $method, $this->guid, $type, $subtype, $action);
		}
		
		return $result;
	}
	
	/**
	 * Remove all subscriptions between the user and the current entity
	 *
	 * @param int             $user_guid the user to subscribe (default: current user)
	 * @param string|string[] $methods   notification method(s)
	 *
	 * @return bool
	 */
	public function removeSubscriptions(int $user_guid = 0, $methods = []): bool {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = (array) $methods;
		
		return _elgg_services()->subscriptions->removeSubscriptions($user_guid, $this->guid, $methods);
	}
	
	/**
	 * Get all the subscriptions to this entity
	 *
	 * @param int             $user_guid user for subscriptions
	 * @param string|string[] $methods   notification method (default: current registered methods)
	 * @param string          $type      entity type
	 * @param string          $subtype   entity subtype
	 * @param string          $action    notification action (eg. 'create')
	 *
	 * @return \ElggRelationship[]
	 */
	public function getSubscriptions(int $user_guid = 0, $methods = [], string $type = null, string $subtype = null, string $action = null): array {
		if ($user_guid === 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		$methods = (array) $methods;
		
		return _elgg_services()->subscriptions->getEntitySubscriptions($this->guid, $user_guid, $methods, $type, $subtype, $action);
	}
	
	/**
	 * Get all entities which are subscribed to notifications about this entity
	 *
	 * @param string|string[] $methods notification methods
	 *
	 * @return \ElggEntity[]
	 */
	public function getSubscribers($methods = []): array {
		$methods = (array) $methods;
		
		return _elgg_services()->subscriptions->getSubscribers($this->guid, $methods);
	}
	
	/**
	 * Normalize subscription methods
	 *
	 * @param string|string[] $methods the methods to normalize
	 *
	 * @throws \InvalidArgumentException
	 * @return string[]
	 */
	protected function normalizeSubscriptionMethods($methods = []): array {
		if (!is_string($methods) && !is_array($methods)) {
			$dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
			$caller = $dbt[1]['function'] ?? 'unknown';
			
			throw new \InvalidArgumentException(elgg_echo('Entity:Subscriptions:InvalidMethodsException', [$caller]));
		}
		
		$methods = (array) $methods;
		
		foreach ($methods as $method) {
			if (!is_string($method) || $method === '') {
				$dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
				$caller = $dbt[1]['function'] ?? 'unknown';
				
				throw new \InvalidArgumentException(elgg_echo('Entity:Subscriptions:InvalidMethodsException', [$caller]));
			}
		}
		
		return $methods ?: _elgg_services()->notifications->getMethods();
	}
}
