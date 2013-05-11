<?php
/**
 * Site notification class
 */

class SiteNotification extends ElggObject {

	const HAS_ACTOR = "hasActor";

	/**
	 * Initialize an instance
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'site_notification';
	}

	/**
	 * Get the actor involved in the notification
	 * 
	 * @return ElggEntity|null
	 */
	public function getActor() {
		$actor = $this->getEntitiesFromRelationship(self::HAS_ACTOR);
		if ($actor) {
			$actor = $actor[0];
		}

		return $actor;
	}

	/**
	 * Set the actor involved in the notification
	 * 
	 * @param ElggEntity $entity Actor
	 */
	public function setActor($entity) {
		$this->addRelationship($entity->guid, self::HAS_ACTOR);
	}

	/**
	 * Set the read status
	 * 
	 * @param bool $read Has the notification been read
	 */
	public function setRead($read) {
		$this->read = $read;
	}

	/**
	 * Has the notification been read?
	 * 
	 * @return bool
	 */
	public function isRead() {
		return (bool)$this->read;
	}
}
