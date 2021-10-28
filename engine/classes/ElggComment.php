<?php
/**
 * \ElggComment
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
	}

	/**
	 * Can a user comment on this object? Always returns false (threaded comments
	 * not yet supported)
	 *
	 * @see \ElggEntity::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 * @since 1.9.0
	 */
	public function canComment($user_guid = 0, $default = false) {
		return false;
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
}
