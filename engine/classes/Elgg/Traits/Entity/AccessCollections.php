<?php

namespace Elgg\Traits\Entity;

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;

/**
 * Bundle all access_collections related functions for an \ElggEntity
 *
 * @since 6.1
 */
trait AccessCollections {
	
	/**
	 * Returns the ACLs owned by the entity
	 *
	 * @param array $options additional options to get the access collections with
	 *
	 * @return \ElggAccessCollection[]
	 *
	 * @see elgg_get_access_collections()
	 * @since 3.0
	 */
	public function getOwnedAccessCollections(array $options = []): array {
		$options['owner_guid'] = $this->guid;
		return _elgg_services()->accessCollections->getEntityCollections($options);
	}
	
	/**
	 * Returns the first ACL owned by the entity with a given subtype
	 *
	 * @param string $subtype subtype of the ACL
	 *
	 * @return \ElggAccessCollection|null
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 *
	 * @since 3.0
	 */
	public function getOwnedAccessCollection(string $subtype): ?\ElggAccessCollection {
		if ($subtype === '') {
			throw new ElggInvalidArgumentException(__METHOD__ . ' requires $subtype to be non empty');
		}
		
		$acls = $this->getOwnedAccessCollections([
			'subtype' => $subtype,
		]);
		
		return elgg_extract(0, $acls);
	}
	
	/**
	 * Remove the membership of all access collections for this entity (if the entity is a user)
	 *
	 * @return bool
	 * @since 1.11
	 */
	public function deleteAccessCollectionMemberships() {
		if (!$this->guid) {
			return false;
		}
		
		if ($this->type !== 'user') {
			return true;
		}
		
		$ac = _elgg_services()->accessCollections;
		
		$collections = $ac->getCollectionsByMember($this->guid);
		if (empty($collections)) {
			return true;
		}
		
		$result = true;
		foreach ($collections as $collection) {
			$result &= $ac->removeUser($this->guid, $collection->id);
		}
		
		return $result;
	}
	
	/**
	 * Remove all access collections owned by this entity
	 *
	 * @return bool
	 * @since 1.11
	 */
	public function deleteOwnedAccessCollections() {
		if (!$this->guid) {
			return false;
		}
		
		$collections = $this->getOwnedAccessCollections();
		if (empty($collections)) {
			return true;
		}
		
		$result = true;
		foreach ($collections as $collection) {
			$result = $result & $collection->delete();
		}
		
		return $result;
	}
}
