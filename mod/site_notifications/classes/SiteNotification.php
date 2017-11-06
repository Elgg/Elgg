<?php
/**
 * Site notification class
 */

class SiteNotification extends ElggObject {

	const HAS_ACTOR = "hasActor";

	/**
	 * {@inheritDoc}
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
		$actor = $this->getEntitiesFromRelationship(['relationship' => self::HAS_ACTOR]);
		if ($actor) {
			$actor = $actor[0];
		}

		return $actor;
	}

	/**
	 * Set the actor involved in the notification
	 *
	 * @param ElggEntity $entity Actor
	 *
	 * @return void
	 */
	public function setActor($entity) {
		$this->addRelationship($entity->guid, self::HAS_ACTOR);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getURL() {
		return (string) $this->url;
	}

	/**
	 * Set the url for the notification
	 *
	 * @param string $url The URL for the notification link
	 *
	 * @return void
	 */
	public function setURL($url) {
		if ($url) {
			$this->url = $url;
		}
	}

	/**
	 * Set the read status
	 *
	 * @param bool $read Has the notification been read
	 *
	 * @return void
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
		return (bool) $this->read;
	}
}
