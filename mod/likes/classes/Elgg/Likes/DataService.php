<?php

namespace Elgg\Likes;

/**
 * @access private
 */
class DataService {

	/**
	 * @var array [GUID => boolean]
	 */
	protected $current_user_likes = array();

	/**
	 * @var array [GUID => int]
	 */
	protected $num_likes = array();

	/**
	 * @param int $guid
	 * @param int $num
	 */
	public function setNumLikes($guid, $num) {
		$this->num_likes[$guid] = (int)$num;
	}

	/**
	 * @param int  $guid
	 * @param bool $is_liked
	 */
	public function setLikedByCurrentUser($guid, $is_liked) {
		$this->current_user_likes[$guid] = (bool)$is_liked;
	}

	/**
	 * @param int $entity_guid
	 * @return bool
	 */
	public function currentUserLikesEntity($entity_guid) {
		if (!isset($this->current_user_likes[$entity_guid])) {
			$this->current_user_likes[$entity_guid] = elgg_annotation_exists($entity_guid, 'likes');
		}
		return $this->current_user_likes[$entity_guid];
	}

	/**
	 * @param \ElggEntity $entity
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
	 * @return DataService
	 */
	public static function instance() {
		static $inst;
		if ($inst === null) {
			$inst = new self();
		}
		return $inst;
	}
}
