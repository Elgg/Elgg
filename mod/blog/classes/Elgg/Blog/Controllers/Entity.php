<?php

namespace Elgg\Blog\Controllers;

use Elgg\Controllers\GenericEntity;
use Elgg\Exceptions\Http\PageNotFoundException;

/**
 * View/edit/add blogs
 *
 * @since 7.1
 */
class Entity extends GenericEntity {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		if ($page === 'edit') {
			$sidebar_view = $this->route?->getOption('sidebar_view');
			if (!empty($sidebar_view) && elgg_view_exists($sidebar_view)) {
				$entity = $this->getEntity(true);
				$revision = $this->getRevision();
				
				if ($revision instanceof \ElggAnnotation) {
					$options['title'] .= ' ' . elgg_echo('blog:edit_revision_notice');
				}
				
				$options['sidebar'] = elgg_view($sidebar_view, [
					'entity' => $entity,
					'revision' => $revision,
					'page' => $page,
				]);
			}
		}
		
		return parent::getPageOptions($page, $options);
	}
	
	/**
	 * Get the blog revision from the route config
	 *
	 * @return \ElggAnnotation|null
	 */
	protected function getRevision(): ?\ElggAnnotation {
		$entity = $this->getEntity();
		
		$revision_id = (int) $this->request?->getParam('revision');
		if (!empty($revision_id)) {
			$revision = elgg_get_annotation_from_id($revision_id);
			if (!$revision instanceof \ElggAnnotation) {
				return null;
			}
			
			if ($revision->entity_guid !== $entity->guid || $revision->name !== 'blog_revision') {
				throw new PageNotFoundException(elgg_echo('blog:error:revision_not_found'));
			}
			
			return $revision;
		}
		
		return null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function editEntity(): string {
		$entity = $this->getEntity(true);
		$entity_type = $this->getEntityType();
		$entity_subtype = $this->getEntitySubtype();
		
		return elgg_view_page('', $this->getPageOptions('edit', [
			'content' => elgg_view_entity_form($entity_type, $entity_subtype, $entity, [
				'body_vars' => [
					'revision' => $this->getRevision(),
				],
			]),
		]));
	}
}
