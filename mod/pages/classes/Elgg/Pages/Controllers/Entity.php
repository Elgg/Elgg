<?php

namespace Elgg\Pages\Controllers;

use Elgg\Controllers\GenericEntity;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\HttpException;

/**
 * View/edit/add pages
 *
 * @since 7.1
 */
class Entity extends GenericEntity {
	
	/**
	 * {@inheritdoc}
	 */
	protected function prepareBreadcrumbs(): void {
		switch ($this->getPage()) {
			case 'add':
				$parent_guid = (int) $this->request->getParam('guid');
				$container = $this->getContainerEntity($parent_guid);
				
				elgg_push_collection_breadcrumbs($this->getEntityType(), $this->getEntitySubtype(), $container);
				
				$parent = $this->getParentEntity($parent_guid);
				if ($parent instanceof \ElggPage) {
					pages_prepare_parent_breadcrumbs($parent);
				}
				return;
			case 'edit':
			case 'view':
				/** @var \ElggPage $entity */
				$entity = $this->getEntity();
				$container = $entity->getContainerEntity();
				if (!$container instanceof \ElggEntity) {
					throw new EntityNotFoundException();
				}
				
				elgg_push_collection_breadcrumbs($this->getEntityType(), $this->getEntitySubtype(), $container);
				pages_prepare_parent_breadcrumbs($entity);
				
				return;
		}
		
		parent::prepareBreadcrumbs();
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		$options = parent::getPageOptions($page, $options);
		
		if (!str_starts_with((string) elgg_extract('filter_id', $options), 'pages/')) {
			$options['filter_id'] = "pages/{$this->getPage()}";
		}
		
		return $options;
	}
	
	/**
	 * Get the parent page entity for a given GUID
	 *
	 * @param int $guid entity GUID to start the search from
	 *
	 * @return \ElggPage|null
	 */
	protected function getParentEntity(int $guid): ?\ElggPage {
		$parent = get_entity($guid);
		
		return $parent instanceof \ElggPage ? $parent : null;
	}
	
	/**
	 * Get the correct container entity for a given GUID
	 *
	 * @param int $guid entity GUID to start the search from
	 *
	 * @return \ElggEntity|null
	 */
	protected function getContainerEntity(int $guid): ?\ElggEntity {
		$container = get_entity($guid);
		
		return $container instanceof \ElggPage ? $container->getContainerEntity() : $container;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addEntity(): string {
		$entity_type = $this->getEntityType();
		$entity_subtype = $this->getEntitySubtype();
		
		$parent_guid = (int) $this->request->getParam('guid');
		$parent = $this->getParentEntity($parent_guid);
		$container = $this->getContainerEntity($parent_guid);
		
		$parent_guid = $parent ? $parent_guid : 0;
		
		if ($parent && !$parent->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		if (!$container || !$container->canWriteToContainer(0, $entity_type, $entity_subtype)) {
			throw new EntityPermissionsException();
		}
		
		elgg_set_page_owner_guid($container->guid);
		
		return elgg_view_page('', $this->getPageOptions('add', [
			'content' => elgg_view_entity_form($entity_type, $entity_subtype, null, [
				'body_vars' => [
					'parent_guid' => $parent_guid,
				],
			]),
			'filter_id' => 'pages/edit',
		]));
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function editEntity(): string {
		/** @var \ElggPage $entity */
		$entity = $this->getEntity(true);
		$entity_type = $this->getEntityType();
		$entity_subtype = $this->getEntitySubtype();
		
		return elgg_view_page('', $this->getPageOptions('edit', [
			'content' => elgg_view_entity_form($entity_type, $entity_subtype, $entity, [
				'body_vars' => [
					'parent_guid' => $entity->getParentGUID(),
				],
			]),
			'filter_id' => 'pages/edit',
		]));
	}
}
