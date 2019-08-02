<?php

namespace Elgg\Likes;

/**
 * Likes dataservice
 *
 * @internal
 */
class DataService {

	/**
	 * @var array [GUID => boolean]
	 */
	protected $current_user_likes = [];

	/**
	 * @var array [GUID => int]
	 */
	protected $num_likes = [];

	/**
	 * Set number of likes
	 *
	 * @param int $guid for guid
	 * @param int $num  number of likes
	 *
	 * @return void
	 */
	public function setNumLikes($guid, $num) {
		$this->num_likes[$guid] = (int) $num;
	}

	/**
	 * Set liked status for an entity for the current logged in user
	 *
	 * @param int  $guid     the entity guid
	 * @param bool $is_liked liked or not
	 *
	 * @return void
	 */
	public function setLikedByCurrentUser($guid, $is_liked) {
		$this->current_user_likes[$guid] = (bool) $is_liked;
	}

	/**
	 * Did the current logged in user like the entity
	 *
	 * @param int $entity_guid entity guid to check
	 *
	 * @return bool
	 */
	public function currentUserLikesEntity($entity_guid) {
		if (!isset($this->current_user_likes[$entity_guid])) {
			$this->current_user_likes[$entity_guid] = elgg_annotation_exists($entity_guid, 'likes');
		}
		return $this->current_user_likes[$entity_guid];
	}

	/**
	 * Get the number of likes for an entity
	 *
	 * @param \ElggEntity $entity the entity to fetch for
	 *
	 * @return int
	 */
	public function getNumLikes(\ElggEntity $entity) {
		$guid = $entity->guid;
		if (!isset($this->num_likes[$guid])) {
			$this->num_likes[$guid] = likes_count($entity);
		}
		return $this->num_likes[$guid];
	}

	/**
	 * Get a DataService instance
	 *
	 * @return \Elgg\Likes\DataService
	 */
	public static function instance() {
		static $inst;
		if ($inst === null) {
			$inst = new self();
		}
		return $inst;
	}
}
