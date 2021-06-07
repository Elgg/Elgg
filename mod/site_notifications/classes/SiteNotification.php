<?php

use Elgg\Notifications\Notification;
use Elgg\Notifications\NotificationEvent;

/**
 * Site notification class
 *
 * @property string $summary            Original notification summary
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
		$this->attributes['access_id'] = ACCESS_PRIVATE;
		
		$this->read = false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getDisplayName() {
		if (!isset($this->title)) {
			return $this->description; // pre Elgg 4.0 contains summary/subject in description;
		}
		
		return parent::getDisplayName();
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
	public function setActor(\ElggEntity $entity) {
		$this->addRelationship($entity->guid, self::HAS_ACTOR);
	}
	
	/**
	 * Set the url for the notification
	 *
	 * @param string $url The URL for the notification link
	 *
	 * @return void
	 */
	public function setURL(string $url) {
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
	public function setRead(bool $read) {
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
	
	/**
	 * Create a site notification from an Elgg notification
	 *
	 * @param Notification      $notification Notification from the notification system
	 * @param NotificationEvent $event        Notification event from the notification system
	 *
	 * @return SiteNotification
	 */
	public static function factory(Notification $notification, NotificationEvent $event = null): SiteNotification {
		$site_notification = new static();
		$site_notification->owner_guid = $notification->getRecipientGUID();
		$site_notification->container_guid = $notification->getRecipientGUID();
		$site_notification->title = $notification->subject;
		$site_notification->summary = $notification->summary;
		$site_notification->description = $notification->body;
		
		if (isset($event)) {
			$object = $event->getObject();
			if ($object instanceof \ElggData) {
				$entity = false;
				switch ($object->getType()) {
					case 'annotation':
					case 'metadata':
						$entity = $object->getEntity();
						break;
					case 'relationship':
						// TODO Add support for linking a notification with a relationship
						break;
					default:
						if ($object instanceof \ElggEntity) {
							$entity = $object;
						}
						break;
				}
				
				if ($entity instanceof \ElggEntity) {
					$site_notification->setLinkedEntity($entity);
				}
			}
		}
		
		if (!empty($notification->url) && $notification->url !== elgg_get_site_url()) {
			$site_notification->setURL($notification->url);
		}
		
		$site_notification->save();
		
		$site_notification->setActor($notification->getSender());
		
		return $site_notification;
	}
}
