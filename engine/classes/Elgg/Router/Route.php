<?php

namespace Elgg\Router;

/**
 * Route Wrapper
 */
class Route extends \Symfony\Component\Routing\Route {

	/**
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * Set matched parameters
	 *
	 * @param array $parameters Parameters
	 *
	 * @return static
	 * @internal
	 */
	public function setMatchedParameters(array $parameters) {
		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * Get matched parameters
	 *
	 * @return array
	 */
	public function getMatchedParameters() {
		return $this->parameters;
	}

	/**
	 * Get matched route name
	 * @return string
	 */
	public function getName() {
		return elgg_extract('_route', $this->parameters);
	}

	/**
	 * Attemps to resolve page owner from route parameters
	 *
	 * @return \ElggEntity|null
	 * @internal
	 */
	public function resolvePageOwner() {

		$params = $this->getMatchedParameters();
		$route_name = $this->getName();

		$route_parts = explode(':', $route_name);

		$from_guid = function ($guid) {
			return elgg_call(ELGG_IGNORE_ACCESS, function() use ($guid) {
				$entity = get_entity($guid);
				if ($entity instanceof \ElggObject) {
					return $entity->getContainerEntity();
				}
				
				return $entity;
			});
		};

		switch ($route_parts[0]) {
			case 'view':
			case 'edit':
				$username = (string) elgg_extract('username', $params);
				if (!empty($username)) {
					return elgg_get_user_by_username($username);
				}

				$guid = (int) elgg_extract('guid', $params);
				if (!empty($guid)) {
					return $from_guid($guid);
				}
				break;

			case 'add':
			case 'collection':
				$username = (string) elgg_extract('username', $params);
				if (!empty($username)) {
					return elgg_get_user_by_username($username);
				}

				$guid = (int) elgg_extract('guid', $params);
				if (!empty($guid)) {
					return $from_guid($guid);
				}

				$container_guid = (int) elgg_extract('container_guid', $params);
				if (!empty($container_guid)) {
					return $from_guid($container_guid);
				}
				break;
				
			default:
				// route name doesn't support auto detection of page_owner
				// but there is information in the route which could support it
				// and the developer requests that detection is tried
				if (!(bool) $this->getDefault('_detect_page_owner')) {
					break;
				}
				
				$username = (string) elgg_extract('username', $params);
				if (!empty($username)) {
					return elgg_get_user_by_username($username);
				}
				
				$guid = (int) elgg_extract('guid', $params);
				if (!empty($guid)) {
					return $from_guid($guid);
				}
				
				$container_guid = (int) elgg_extract('container_guid', $params);
				if (!empty($container_guid)) {
					return $from_guid($container_guid);
				}
				break;
		}
	}
}
