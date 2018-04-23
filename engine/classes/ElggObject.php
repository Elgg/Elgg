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
	public function getType() {
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
	public function getDisplayName() {
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayName($display_name) {
		$this->title = $display_name;
	}

	/**
	 * Can a user comment on this object?
	 *
	 * @see \ElggEntity::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0, $default = null) {
		$result = parent::canComment($user_guid, $default);
		if ($result !== null) {
			return $result;
		}

		if ($user_guid == 0) {
			$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		// must be logged in to comment
		if (!$user_guid) {
			return false;
		}

		// must be member of group
		$container = $this->getContainerEntity();
		if ($container instanceof \ElggGroup) {
			if (!$container->canWriteToContainer($user_guid)) {
				return false;
			}
		}

		// no checks on read access since a user cannot see entities outside his access
		return true;
	}
}
