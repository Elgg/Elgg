<?php

/**
 * Site notification class
 *
 * @property string $url                Url to the target of the notification
 * @property bool   $read               Has this notification been read yet
 * @property int    $linked_entity_guid Entity linked to this notification
 */
class SiteNotification extends ElggObject {

	const HAS_ACTOR = 'hasActor';

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
		if (isset($this->url)) {
			return (string) $this->url;
		}
		
		$linked_entity = $this->getLinkedEntity();
		if ($linked_entity instanceof ElggEntity) {
			return $linked_entity->getURL();
		}
		
		return '';
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
	
	/**
	 * Link a notification to an entity
	 *
	 * @param \ElggEntity $entity the entity linked to this notification
	 *
	 * @return void
	 */
	public function setLinkedEntity(\ElggEntity $entity) {
		$this->linked_entity_guid = $entity->guid;
	}
	
	/**
	 * Get the linked entity for this notification
	 *
	 * @return \ElggEntity|false
	 */
	public function getLinkedEntity() {
		return get_entity($this->linked_entity_guid);
	}
}
