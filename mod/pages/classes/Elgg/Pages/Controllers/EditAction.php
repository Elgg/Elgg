<?php

namespace Elgg\Pages\Controllers;

/**
 * Create or edit a page
 *
 * @since 6.2
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	/**
	 * {@inheritdoc}
	 */
	protected function sanitize(): void {
		parent::sanitize();
		
		$this->request->setParam('guid', $this->request->getParam('guid', $this->request->getParam('page_guid'))); // @todo remove in Elgg 7.0
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(array $skip_field_names = []): void {
		$skip_field_names = ['parent_guid'];
		if (!$this->entity->getOwnerEntity()?->canEdit()) {
			// don't change access if not an owner/admin
			$skip_field_names[] = 'access_id';
			$skip_field_names[] = 'write_access_id';
		}
		
		parent::execute($skip_field_names);
		
		$guid = $this->entity->guid;
		$parent_guid = (int) $this->request->getParam('parent_guid');
		
		if (!$this->isNewEntity() && $parent_guid && $parent_guid !== $guid) {
			// Check if parent isn't below the page in the tree
			$tree_page = get_entity($parent_guid);
			while ($tree_page instanceof \ElggPage && $guid !== $tree_page->guid) {
				$tree_page = $tree_page->getParentEntity();
			}
			
			// If is below, bring all child elements forward
			if ($tree_page instanceof \ElggPage && ($guid === $tree_page->guid)) {
				$previous_parent = $this->entity->getParentGUID();
				
				$children = elgg_get_entities([
					'type' => 'object',
					'subtype' => 'page',
					'metadata_name_value_pairs' => [
						'parent_guid' => $this->entity->guid,
					],
					'limit' => false,
					'batch' => true,
					'batch_inc_offset' => false,
				]);
				
				/* @var $child \ElggPage */
				foreach ($children as $child) {
					$child->setParentByGUID($previous_parent);
				}
			}
		}

		// set parent
		$this->entity->setParentByGUID($parent_guid);
		
		// Now save description as an annotation
		$this->entity->annotate('page', (string) $this->entity->description, $this->entity->access_id);
	}
}
