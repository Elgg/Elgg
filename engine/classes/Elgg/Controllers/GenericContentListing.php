<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\Gatekeeper\GroupToolGatekeeperException;
use Elgg\Exceptions\Http\ValidationException;

/**
 * Generic controller to handle entity listing routes
 *
 * @since 7.0
 */
class GenericContentListing extends GenericContent {
	
	protected ?\ElggEntity $page_owner = null;
	
	/**
	 * {@inheritdoc}
	 */
	final protected function handleRequest(\Elgg\Request $request): \Elgg\Http\Response {
		$this->page_owner = $this->route?->resolvePageOwner();
		
		$parsed_route = $this->parseRoute();
		
		elgg_register_title_button('add', $parsed_route['type'], $parsed_route['subtype']);
		
		$options = $this->getListingOptions($parsed_route['page'], [
			'type' => $parsed_route['type'],
			'subtype' => $parsed_route['subtype'],
		]);
		
		$listing_function = 'list' . str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $parsed_route['page'])));
		if (!is_callable([$this, $listing_function])) {
			throw new ValidationException('Unsupported route name configuration');
		}
		
		return elgg_ok_response($this->{$listing_function}($options));
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function assertValidRoute(): void {
		$route_parts = $this->getRouteParts();
		
		if (!in_array($route_parts[0], ['default', 'collection'])) {
			throw new ValidationException('Unsupported route name configuration');
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPage(): string {
		return $this->getRouteParts()[3] ?? 'all';
	}
	
	/**
	 * Parse the route name to usable parts
	 *
	 * @return array
	 * @throws ValidationException
	 */
	final protected function parseRoute(): array {
		$route_parts = $this->getRouteParts();
		
		return [
			'type' => $route_parts[1],
			'subtype' => $route_parts[2],
			'page' => $route_parts[3] ?? 'all',
		];
	}
	
	/**
	 * Get the name of the group tool option for group routes
	 *
	 * @return string|null
	 */
	protected function getGroupToolOption(): ?string {
		return $this->route?->getOption('group_tool');
	}
	
	/**
	 * Get additional options to use for elgg_list_entities()
	 *
	 * @param string $page    for which page to get the options ('all', 'owner', 'group', 'friends')
	 * @param array  $options options based on the route information
	 *
	 * @return array
	 * @see elgg_list_entities()
	 */
	protected function getListingOptions(string $page, array $options): array {
		return $options;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		if (!isset($options['filter_id']) && $page === 'group') {
			$options['filter_id'] = "{$this->getEntitySubtype()}/group";
		}
		
		if (!isset($options['filter_value'])) {
			$filter_value = $page;
			switch ($page) {
				case 'friends':
					$filter_value = $this->page_owner?->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none';
					break;
				case 'group':
					$filter_value = 'all';
					break;
				case 'owner':
					$filter_value = $this->page_owner?->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none';
					break;
			}
			
			$options['filter_value'] = $filter_value;
		}
		
		if (!isset($options['sidebar'])) {
			$sidebar_view = $this->route?->getOption('sidebar_view');
			if (!empty($sidebar_view) && elgg_view_exists($sidebar_view)) {
				$options['sidebar'] = elgg_view($sidebar_view, [
					'page' => $page,
					'entity' => $this->page_owner,
				]);
			}
		}
		
		if (!isset($options['title'])) {
			if ($page === 'owner') {
				$options['title'] = elgg_echo("collection:{$this->getEntityType()}:{$this->getEntitySubtype()}:{$page}", [$this->page_owner?->getDisplayName()]);
			} else {
				$options['title'] = elgg_echo("collection:{$this->getEntityType()}:{$this->getEntitySubtype()}:{$page}");
			}
		}
		
		return $options;
	}
	
	/**
	 * All content item listing
	 *
	 * for the routes:
	 * - default:<type>:<subtype>
	 * - collection:<type>:<subtype>:all
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 */
	protected function listAll(array $options): string {
		return elgg_view_page('', $this->getPageOptions('all', [
			'content' => elgg_view('page/list/all', [
				'options' => $options,
				'page' => 'all',
			]),
			'filter_value' => 'all',
		]));
	}
	
	/**
	 * Friends content item listing
	 *
	 * for the route:
	 * - collection:<type>:<subtype>:friends
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listFriends(array $options): string {
		if (!$this->page_owner instanceof \ElggUser) {
			throw new BadRequestException();
		}
		
		elgg_push_collection_breadcrumbs($options['type'], $options['subtype'], $this->page_owner, true);
		
		$friends_options = [
			'relationship' => 'friend',
			'relationship_guid' => $this->page_owner->guid,
			'relationship_join_on' => 'owner_guid',
		];
		
		return elgg_view_page('', $this->getPageOptions('friends', [
			'content' => elgg_view('page/list/all', [
				'entity' => $this->page_owner,
				'options' => array_merge($options, $friends_options),
				'page' => 'friends',
			]),
		]));
	}
	
	/**
	 * Group content item listing
	 *
	 * for the route:
	 * - collection:<type>:<subtype>:group
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 * @throws GroupToolGatekeeperException
	 */
	protected function listGroup(array $options): string {
		if (!$this->page_owner instanceof \ElggGroup) {
			throw new BadRequestException();
		}
		
		$group_tool = $this->getGroupToolOption();
		if (isset($group_tool)) {
			elgg_group_tool_gatekeeper($group_tool);
		}
		
		elgg_push_collection_breadcrumbs($options['type'], $options['subtype'], $this->page_owner);
		
		$group_options = [
			'container_guid' => $this->page_owner->guid,
			'preload_containers' => false,
		];
		
		return elgg_view_page('', $this->getPageOptions('group', [
			'content' => elgg_view('page/list/all', [
				'entity' => $this->page_owner,
				'options' => array_merge($options, $group_options),
				'page' => 'group',
			]),
		]));
	}
	
	/**
	 * Owner content item listing
	 *
	 * for the route:
	 * - collection:<type>:<subtype>:owner
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listOwner(array $options): string {
		if (!$this->page_owner instanceof \ElggUser) {
			throw new BadRequestException();
		}
		
		elgg_push_collection_breadcrumbs($options['type'], $options['subtype'], $this->page_owner);
		
		$owner_options = [
			'owner_guid' => $this->page_owner->guid,
			'preload_owners' => false,
		];
		
		return elgg_view_page('', $this->getPageOptions('owner', [
			'content' => elgg_view('page/list/all', [
				'entity' => $this->page_owner,
				'options' => array_merge($options, $owner_options),
				'page' => 'owner',
			]),
		]));
	}
}
