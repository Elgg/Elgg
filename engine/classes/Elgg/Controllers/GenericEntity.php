<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Exceptions\HttpException;
use Elgg\Http\Response;

/**
 * Generic controller to handle entity view/edit/add routes
 *
 * @since 7.1
 */
class GenericEntity extends GenericContent {
	
	protected ?\ElggEntity $entity = null;
	
	/**
	 * {@inheritdoc}
	 */
	final protected function handleRequest(\Elgg\Request $request): Response {
		$view_function = lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $this->getPage())))) . 'Entity';
		if (!is_callable([$this, $view_function])) {
			throw new ValidationException('Unsupported route name configuration');
		}
		
		$this->prepareBreadcrumbs();
		
		return elgg_ok_response($this->{$view_function}());
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function assertValidRoute(): void {
		$route_parts = $this->getRouteParts();
		
		if (!in_array($route_parts[0], ['add', 'edit', 'view'])) {
			throw new ValidationException('Unsupported route name configuration');
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPage(): string {
		return $this->getRouteParts()[0];
	}
	
	/**
	 * Get the entity from the request
	 *
	 * @param bool $validate_can_edit flag to check canEdit access
	 *
	 * @return \ElggEntity
	 * @throws HttpException
	 */
	protected function getEntity(bool $validate_can_edit = false): \ElggEntity {
		if (isset($this->entity) && !$validate_can_edit) {
			return $this->entity;
		}
		
		$guid = (int) $this->request->getParam('guid');
		
		$this->entity = elgg_entity_gatekeeper($guid, $this->getEntityType(), $this->getEntitySubtype(), $validate_can_edit);
		
		return $this->entity;
	}
	
	/**
	 * Prepare breadcrumbs
	 *
	 * @return void
	 * @throws HttpException
	 */
	protected function prepareBreadcrumbs(): void {
		switch ($this->getPage()) {
			case 'add':
				$entity_type = $this->getEntityType();
				$entity_subtype = $this->getEntitySubtype();
				$page_owner = elgg_get_page_owner_entity();
				
				elgg_push_collection_breadcrumbs($entity_type, $entity_subtype, $page_owner);
				break;
			case 'edit':
				elgg_push_entity_breadcrumbs($this->getEntity(true));
				break;
			case 'view':
				elgg_push_entity_breadcrumbs($this->getEntity());
				break;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		if (!isset($options['filter_id'])) {
			if ($page === 'view') {
				$options['filter_id'] = "{$this->getEntitySubtype()}/view";
			} else {
				$options['filter_id'] = "{$this->getEntitySubtype()}/edit";
			}
		}
		
		if (!isset($options['sidebar'])) {
			$sidebar_view = $this->route?->getOption('sidebar_view');
			if (!empty($sidebar_view) && elgg_view_exists($sidebar_view)) {
				$entity = null;
				switch ($page) {
					case 'edit':
						$entity = $this->getEntity(true);
						break;
					case 'view':
						$entity = $this->getEntity();
						break;
				}
				
				$options['sidebar'] = elgg_view($sidebar_view, [
					'entity' => $entity,
					'page' => $page,
				]);
			}
		}
		
		if (!isset($options['title'])) {
			if ($page === 'view') {
				$options['title'] = $this->getEntity()->getDisplayName();
			} else {
				$options['title'] = elgg_echo("{$page}:{$this->getEntityType()}:{$this->getEntitySubtype()}");
			}
		}
		
		return $options;
	}
	
	/**
	 * Render an entity add page
	 *
	 * @return string
	 * @throws HttpException
	 */
	protected function addEntity(): string {
		$entity_type = $this->getEntityType();
		$entity_subtype = $this->getEntitySubtype();
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner->canWriteToContainer(0, $entity_type, $entity_subtype)) {
			throw new EntityPermissionsException();
		}
		
		return elgg_view_page('', $this->getPageOptions('add', [
			'content' => elgg_view_entity_form($entity_type, $entity_subtype),
		]));
	}
	
	/**
	 * Render an entity edit page
	 *
	 * @return string
	 * @throws HttpException
	 */
	protected function editEntity(): string {
		$entity = $this->getEntity(true);
		$entity_type = $this->getEntityType();
		$entity_subtype = $this->getEntitySubtype();
		
		return elgg_view_page('', $this->getPageOptions('edit', [
			'content' => elgg_view_entity_form($entity_type, $entity_subtype, $entity),
		]));
	}
	
	/**
	 * Render the entity view page
	 *
	 * @return string
	 * @throws HttpException
	 */
	protected function viewEntity(): string {
		$entity = $this->getEntity();
		
		return elgg_view_page('', $this->getPageOptions('view', [
			'content' => elgg_view_entity($entity),
			'entity' => $entity,
		]));
	}
}
