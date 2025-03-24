<?php

/**
 * Page class
 *
 * @property int $parent_guid     The GUID of the parent page
 * @property int $write_access_id The access_id which allows other users to edit this page
 *
 * @since 3.0
 */
class ElggPage extends ElggObject {
	
	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = 'page';
		
		// set default parent (this makes it a top page)
		$this->parent_guid = 0;
	}
	
	/**
	 * Is this a top page in the tree
	 *
	 * @return bool
	 *
	 * @since 3.0
	 */
	public function isTopPage(): bool {
		return empty($this->parent_guid);
	}
	
	/**
	 * Get the top parent entity of this page
	 *
	 * @return \ElggPage
	 *
	 * @since 3.1
	 */
	public function getTopParentEntity(): \ElggPage {
		$top_entity = $this;
		while (!$top_entity->isTopPage()) {
			$new_top = $top_entity->getParentEntity();
			if (!$new_top) {
				break;
			}
			
			$top_entity = $new_top;
		}
		
		return $top_entity;
	}
	
	/**
	 * Get the parent entity of this page
	 *
	 * @return \ElggPage|null
	 *
	 * @since 3.0
	 */
	public function getParentEntity(): ?\ElggPage {
		if (empty($this->parent_guid)) {
			return null;
		}
		
		$parent = get_entity($this->getParentGUID());
		return $parent instanceof \ElggPage ? $parent : null;
	}
	
	/**
	 * Get the GUID of the parent entity
	 *
	 * @return int 0 if no parent
	 */
	public function getParentGUID(): int {
		return (int) $this->parent_guid;
	}
	
	/**
	 * Set a new parent entity from a GUID
	 *
	 * @param int $guid the GUID of an ElggPage or 0
	 *
	 * @return bool
	 *
	 * @since 3.0
	 */
	public function setParentByGUID(int $guid): bool {
		if (empty($guid)) {
			$this->parent_guid = 0;
			return true;
		}
		
		if ($guid === $this->guid) {
			return false;
		}
		
		$new_parent = get_entity($guid);
		return $this->setParentEntity($new_parent);
	}
	
	/**
	 * Set the new parent entity
	 *
	 * @param ElggPage|null $entity the new parent entity. Eighter an ElggPage or null
	 *
	 * @return bool
	 *
	 * @since 3.0
	 */
	public function setParentEntity(?\ElggPage $entity = null): bool {
		if (empty($entity)) {
			$this->parent_guid = 0;
			return true;
		}
		
		if (empty($entity->guid) || $entity->guid === $this->guid) {
			return false;
		}
		
		$this->parent_guid = $entity->guid;
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(bool $recursive = true, ?bool $persistent = null): bool {
		$parent_guid = $this->getParentGUID();
		$result = parent::delete($recursive, $persistent);
		if ($result) {
			$this->moveChildPages($parent_guid);
		}
		
		return $result;
	}
	
	/**
	 * Move child pages up one level
	 *
	 * @param int $parent_guid new parent GUID
	 *
	 * @return void
	 * @since 6.0
	 */
	protected function moveChildPages(int $parent_guid): void {
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function () use ($parent_guid) {
			/* @var $children \ElggBatch */
			$children = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name_value_pairs' => [
					'parent_guid' => $this->guid,
				],
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			
			/* @var $child ElggPage */
			foreach ($children as $child) {
				if (!$child->setParentByGUID($parent_guid)) {
					$children->reportFailure();
				}
			}
		});
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('pages:title'),
			'name' => 'title',
			'required' => true,
		];
		
		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('pages:description'),
			'name' => 'description',
		];
		
		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('pages:tags'),
			'name' => 'tags',
		];
		
		$result[] = [
			'#type' => 'pages/parent',
			'#label' => elgg_echo('pages:parent_guid'),
			'name' => 'parent_guid',
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access:read'),
			'name' => 'access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'page',
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access:write'),
			'name' => 'write_access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'page',
			'purpose' => 'write',
			'entity_allows_comments' => false, // no access change warning for write access input
		];
		
		return $result;
	}
}
