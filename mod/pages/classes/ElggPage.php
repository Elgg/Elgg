<?php

/**
 * Page class
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
	public function isTopPage() {
		return empty($this->parent_guid);
	}
	
	/**
	 * Get the parent entity of this page
	 *
	 * @return false|ElggPage
	 *
	 * @since 3.0
	 */
	public function getParentEntity() {
		
		if (empty($this->parent_guid)) {
			return false;
		}
		
		$parent = get_entity($this->parent_guid);
		if ($parent instanceof ElggPage) {
			return $parent;
		}
		
		return false;
	}
	
	/**
	 * Get the GUID of the parent entity
	 *
	 * @return int 0 if no parent
	 */
	public function getParentGUID() {
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
	public function setParentByGUID($guid) {
		$guid = (int) $guid;
		
		if (empty($guid)) {
			$this->parent_guid = 0;
			return true;
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
	public function setParentEntity($entity) {
		
		if (empty($entity)) {
			$this->parent_guid = 0;
			return true;
		}
		
		if (!$entity instanceof ElggPage || empty($entity->guid)) {
			return false;
		}
		
		$this->parent_guid = $entity->guid;
		return true;
	}
}
