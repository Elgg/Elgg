<?php
/**
 * Extended class to override the time_created
 */
class ElggBlog extends ElggObject {

	/**
	 * Set subtype to blog.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "blog";
	}

	/**
	 * @todo this won't work until we have date l10n working.
	 * Rewrite the time created to be publish time.
	 * This is a bit dirty, but required for proper sorting.
	 */
//	public function save() {
//		if (parent::save()) {
//			global $CONFIG;
//
//			// try to grab the publish date, but default to now.
//			foreach (array('publish_date', 'time_created') as $field) {
//				if (isset($this->$field) && $this->field) {
//					$published = $this->field;
//					break;
//				}
//			}
//			if (!$published) {
//				$published = time();
//			}
//
//			$sql = "UPDATE {$CONFIG->dbprefix}entities SET time_created = '$published', time_updated = '$published' WHERE guid = '{$this->getGUID()}'";
//			return update_data($sql);
//		}
//
//		return FALSE;
//	}

	/**
	 * Can a user comment on this blog?
	 *
	 * @see ElggObject::canComment()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		$result = parent::canComment($user_guid);
		if ($result == false) {
			return $result;
		}

		if ($this->comments_on == 'Off') {
			return false;
		}
		
		return true;
	}

}