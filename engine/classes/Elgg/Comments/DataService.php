<?php

namespace Elgg\Comments;

/**
 * Comments dataservice
 *
 * @since 4.1
 * @internal
 */
class DataService {

	/**
	 * @var array [GUID => int]
	 */
	protected $counts = [];

	/**
	 * Set number of comments
	 *
	 * @param int $guid for guid
	 * @param int $num  number of comments
	 *
	 * @return void
	 */
	public function setCommentsCount(int $guid, int $num): void {
		$this->counts[$guid] = $num;
	}

	/**
	 * Get the number of comments for an entity
	 *
	 * @param \ElggEntity $entity the entity to fetch for
	 *
	 * @return int
	 */
	public function getCommentsCount(\ElggEntity $entity): int {
		$guid = $entity->guid;
		if (!isset($this->counts[$guid])) {
			$this->counts[$guid] = elgg_count_entities([
				'type' => 'object',
				'subtype' => 'comment',
				'container_guid' => $entity->guid,
				'distinct' => false,
			]);
		}
		
		return $this->counts[$guid];
	}

	/**
	 * Removes already counted comments from list of guids
	 *
	 * @param array $guids array of guids
	 *
	 * @return array
	 */
	public function filterGuids(array $guids): array {
		foreach ($guids as $key => $guid) {
			if (!isset($this->counts[$guid])) {
				continue;
			}
			
			unset($guids[$key]);
		}
		
		return array_values($guids);
	}

	/**
	 * Get a DataService instance
	 *
	 * @return \Elgg\Comments\DataService
	 */
	public static function instance(): self {
		static $inst;
		if ($inst === null) {
			$inst = new self();
		}
		
		return $inst;
	}
}
