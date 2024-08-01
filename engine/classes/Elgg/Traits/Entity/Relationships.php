<?php

namespace Elgg\Traits\Entity;

/**
 * Bundle all relationship related functions for an \ElggEntity
 *
 * @since 6.1
 */
trait Relationships {
	
	/**
	 * Add a relationship between this and another entity.
	 *
	 * @tip Read the relationship like "This entity is a $relationship of $guid_two."
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 * @throws \Elgg\Exceptions\LengthException
	 */
	public function addRelationship(int $guid_two, string $relationship): bool {
		$rel = new \ElggRelationship();
		$rel->guid_one = $this->guid;
		$rel->relationship = $relationship;
		$rel->guid_two = $guid_two;
		
		return $rel->save();
	}
	
	/**
	 * Check if this entity has a relationship with another entity
	 *
	 * @tip Read the relationship like "This entity is a $relationship of $guid_two."
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function hasRelationship(int $guid_two, string $relationship): bool {
		return (bool) _elgg_services()->relationshipsTable->check($this->guid, $relationship, $guid_two);
	}
	
	/**
	 * Return the relationship if this entity has a relationship with another entity
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return \ElggRelationship|null
	 * @since 4.3
	 */
	public function getRelationship(int $guid_two, string $relationship): ?\ElggRelationship {
		return _elgg_services()->relationshipsTable->check($this->guid, $relationship, $guid_two) ?: null;
	}
	
	/**
	 * Gets an array of entities with a relationship to this entity.
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity
	 *
	 * @return \ElggEntity[]|int|mixed
	 * @see elgg_get_entities()
	 */
	public function getEntitiesFromRelationship(array $options = []) {
		$options['relationship_guid'] = $this->guid;
		return elgg_get_entities($options);
	}
	
	/**
	 * Gets the number of entities from a specific relationship type
	 *
	 * @param string $relationship         Relationship type (eg "friends")
	 * @param bool   $inverse_relationship Invert relationship
	 *
	 * @return int
	 */
	public function countEntitiesFromRelationship(string $relationship, bool $inverse_relationship = false): int {
		return elgg_count_entities([
			'relationship' => $relationship,
			'relationship_guid' => $this->guid,
			'inverse_relationship' => $inverse_relationship,
		]);
	}
	
	/**
	 * Remove a relationship
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 */
	public function removeRelationship(int $guid_two, string $relationship): bool {
		return _elgg_services()->relationshipsTable->remove($this->guid, $relationship, $guid_two);
	}
	
	/**
	 * Remove all relationships to or from this entity.
	 *
	 * If you pass a relationship name, only relationships matching that name will be deleted.
	 *
	 * @warning Calling this with no $relationship will clear all relationships with this entity.
	 *
	 * @param string $relationship         (optional) The name of the relationship to remove
	 * @param bool   $inverse_relationship (optional) Inverse the relationship
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function removeAllRelationships(string $relationship = '', bool $inverse_relationship = false): bool {
		return _elgg_services()->relationshipsTable->removeAll($this->guid, $relationship, $inverse_relationship);
	}
}
