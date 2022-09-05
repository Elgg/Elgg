<?php
/**
 * An object entity
 *
 * Elgg objects are the most common means of storing information in the database.
 * They are a child class of \ElggEntity, so receive all the benefits of the Entities,
 * but also include a title and description field.
 *
 * An \ElggObject represents a row from the entities table
 *
 * @property string $title       The title, name, or summary of this object
 * @property string $description The body, description, or content of the object
 * @property array  $tags        Tags that describe the object (metadata)
 */
class ElggObject extends \ElggEntity {

	/**
	 * {@inheritdoc}
	 */
	public function getType(): string {
		return 'object';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject(\Elgg\Export\Entity $object) {
		$object = parent::prepareObject($object);
		$object->title = $this->getDisplayName();
		$object->description = $this->description;
		$object->tags = $this->tags ? $this->tags : [];
		return $object;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName(): string {
		return (string) $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayName(string $display_name): void {
		$this->title = $display_name;
	}
}
