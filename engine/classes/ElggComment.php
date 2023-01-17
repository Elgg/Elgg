<?php
/**
 * \ElggComment
 *
 * @property int $level       depth of the comment (default 1 = top level)
 * @property int $parent_guid direct parent of the comment
 * @property int $thread_guid reference to the top comment
 *
 * @since 1.9.0
 */
class ElggComment extends \ElggObject {
	
	/**
	 * Set subtype to comment
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'comment';
		
		$this->level = 1;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function delete($recursive = true) {
		$result = parent::delete($recursive);
		
		if ($result) {
			// remove the threaded comments directly below this comment
			elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($recursive) {
				$children = elgg_get_entities([
					'type' => 'object',
					'subtype' => 'comment',
					'limit' => false,
					'batch' => true,
					'batch_inc_offset' => false,
					'metadata_name_value_pairs' => [
						'name' => 'parent_guid',
						'value' => $this->guid,
					],
				]);
				
				/* @var $child \ElggComment */
				foreach ($children as $child) {
					$child->delete($recursive);
				}
			});
		}
		
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function canComment($user_guid = 0, $default = null) {
		if ($this->getLevel() >= (int) elgg_get_config('comments_max_depth')) {
			return false;
		}
		
		$container = $this->getContainerEntity();
		if (!$container instanceof ElggEntity) {
			return false;
		}
		
		return $container->canComment($user_guid, $default);
	}
	
	/**
	 * Is this comment created by the same owner as the content of the item being commented on
	 *
	 * @return bool
	 * @since 4.1
	 */
	public function isCreatedByContentOwner(): bool {
		return elgg_call(ELGG_IGNORE_ACCESS, function() {
			$container = $this->getContainerEntity();
			if (!$container instanceof ElggEntity) {
				return false;
			}
			
			return $container->owner_guid === $this->owner_guid;
		});
	}
	
	/**
	 * Get the depth level of the comment
	 *
	 * @return int 1: toplevel, 2: first level, etc
	 * @since 4.1
	 */
	public function getLevel(): int {
		return isset($this->level) ? (int) $this->level : 1;
	}
	
	/**
	 * Return the thread GUID this comment is a part of
	 *
	 * @return int
	 * @since 4.1
	 */
	public function getThreadGUID(): int {
		if (isset($this->thread_guid)) {
			return (int) $this->thread_guid;
		}
		
		return $this->guid;
	}
	
	/**
	 * Return the thread (top-level) comment
	 *
	 * @return \ElggComment
	 * @since 4.1
	 */
	public function getThreadEntity(): ?\ElggComment {
		$entity = get_entity($this->getThreadGUID());
		return $entity instanceof \ElggComment ? $entity : null;
	}
}
