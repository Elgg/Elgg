<?php

namespace Elgg\Comments;

use Elgg\Database\Clauses\OrderByClause;

/**
 * Comments thread preloader
 *
 * @since 4.1
 */
class ThreadPreloaderService {

	/**
	 * @var \ElggComment[]
	 */
	protected $children;
	
	/**
	 * Preload the comment threads for the given comments
	 *
	 * @param \ElggComment[] $comments top level comments
	 *
	 * @return void
	 */
	public function preloadThreads(array $comments): void {
		if (empty($comments)) {
			return;
		}
			
		$this->children = [];
		
		$guids = [];
		$container_guid = 0;
		foreach ($comments as $comment) {
			$guids[] = $comment->guid;
			$container_guid = $comment->container_guid;
		}
		
		$batch = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'limit' => false,
			'batch' => true,
			'container_guid' => $container_guid,
			'metadata_name_value_pairs' => [
				'thread_guid' => $guids,
			],
			'order_by' => new OrderByClause('e.time_created', 'ASC'),
			'preload_owners' => true,
		]);
		
		/* @var $comment \ElggComment */
		foreach ($batch as $comment) {
			$parent_guid = (int) $comment->parent_guid;
			if (!isset($this->children[$parent_guid])) {
				$this->children[$parent_guid] = [];
			}
			
			$this->children[$parent_guid][] = $comment;
		}
	}
	
	/**
	 * Get the children of a comment
	 *
	 * @param int $comment_guid the parent comment
	 *
	 * @return \ElggComment[]
	 */
	public function getChildren(int $comment_guid): array {
		if (!isset($this->children)) {
			$comment = get_entity($comment_guid);
			if ($comment instanceof \ElggComment) {
				$this->preloadThreads([$comment->getThreadEntity()]);
			}
		}
		
		return elgg_extract($comment_guid, $this->children, []);
	}
}
