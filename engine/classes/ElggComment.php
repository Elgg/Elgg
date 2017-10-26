<?php
/**
 * \ElggComment
 *
 * @package    Elgg.Core
 * @subpackage Comments
 * @since      1.9.0
 */
class ElggComment extends \ElggObject {

	/**
	 * Set subtype to comment
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "comment";
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
	 * Update container entity last action on successful save.
	 *
	 * @param bool $update_last_action Update the container entity's last_action field
	 * @return bool|int
	 */
	public function save($update_last_action = true) {
		$result = parent::save();
		if ($result && $update_last_action) {
			$container = $this->getContainerEntity();
			if ($container) {
				$container->updateLastAction($this->time_updated);
			}
		}
		return $result;
	}
}
