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
			$entity = get_entity($guid);
			if ($entity instanceof \ElggUser || $entity instanceof \ElggGroup) {
				return $entity;
			} else if ($entity instanceof \ElggObject) {
				return $entity->getContainerEntity();
			}
		};

		switch ($route_parts[0]) {
			case 'view' :
			case 'edit' :
				$username = elgg_extract('username', $params);
				if ($username) {
					return get_user_by_username($username) ?: null;
				}

				$guid = elgg_extract('guid', $params);
				if ($guid) {
					return $from_guid($guid);
				}
				break;

			case 'add' :
			case 'collection' :
				$username = elgg_extract('username', $params);
				if ($username) {
					return get_user_by_username($username) ?: null;
				}

				$guid = elgg_extract('guid', $params);
				if ($guid) {
					return $from_guid($guid);
				}

				$container_guid = elgg_extract('container_guid', $params);
				if ($container_guid) {
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
				
				$username = elgg_extract('username', $params);
				if ($username) {
					return get_user_by_username($username) ?: null;
				}
				
				$guid = elgg_extract('guid', $params);
				if ($guid) {
					return $from_guid($guid);
				}
				
				$container_guid = elgg_extract('container_guid', $params);
				if ($container_guid) {
					return $from_guid($container_guid);
				}
				break;
		}
	}
}
