<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Exceptions\HttpException;
use Elgg\Http\Response;

/**
 * Generic base controller for content listing and content view/edit/add
 *
 * @since 7.1
 */
abstract class GenericContent {
	
	protected ?\Elgg\Request $request = null;
	
	protected ?\Elgg\Router\Route $route = null;
	
	protected ?array $route_parts = null;
	
	/**
	 * Handle a request
	 *
	 * @param \Elgg\Request $request the Elgg request
	 *
	 * @return Response
	 * @throws HttpException
	 */
	final public function __invoke(\Elgg\Request $request): Response {
		$this->request = $request;
		$this->route = $request->getHttpRequest()?->getRoute();
		
		$this->getRouteParts();
		$this->assertValidRoute();
		
		return $this->handleRequest($request);
	}
	
	/**
	 * Validate that route is supported
	 *
	 * @return void
	 * @throws ValidationException
	 */
	abstract protected function assertValidRoute(): void;
	
	/**
	 * Handle the request
	 *
	 * @param \Elgg\Request $request the Elgg request
	 *
	 * @return Response
	 * @throws HttpException
	 */
	abstract protected function handleRequest(\Elgg\Request $request): Response;
	
	/**
	 * Get additional options to use when viewing a page
	 *
	 * @param string $page    for which page to get the options ('all', 'owner', 'group', 'friends', 'view', 'add', etc.)
	 * @param array  $options current page options
	 *
	 * @return array
	 * @see elgg_view_page()
	 */
	abstract protected function getPageOptions(string $page, array $options): array;
	
	/**
	 * Parse the route name to usable parts
	 *
	 * @return array
	 * @throws ValidationException
	 */
	final protected function getRouteParts(): array {
		if (isset($this->route_parts)) {
			return $this->route_parts;
		}
		
		$route_name = $this->route?->getName();
		if (elgg_is_empty($route_name)) {
			throw new ValidationException('Missing route name');
		}
		
		$name_parts = explode(':', $route_name);
		if (count($name_parts) < 3) {
			throw new ValidationException('Unsupported route name configuration');
		}
		
		$this->route_parts = $name_parts;
		return $this->route_parts;
	}
	
	/**
	 * Get the entity type from the route name
	 *
	 * eq. 'collection:object:blog:all' => 'object'
	 * eq. 'view:object:blog' => 'object'
	 *
	 * @return string
	 * @throws ValidationException
	 */
	protected function getEntityType(): string {
		return $this->getRouteParts()[1];
	}
	
	/**
	 * Get the entity subtype from the route name
	 *
	 * eq. 'collection:object:blog:all' => 'blog'
	 * eq. 'view:object:blog' => 'blog'
	 *
	 * @return string
	 * @throws ValidationException
	 */
	protected function getEntitySubtype(): string {
		return $this->getRouteParts()[2];
	}
	
	/**
	 * Get the page from the route name
	 *
	 * @return string
	 * @throws ValidationException
	 */
	abstract protected function getPage(): string;
}
