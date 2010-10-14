<?php
/**
 * Extended class to override the time_created
 */
class ElggBlog extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();

		// override the default file subtype.
		$this->attributes['subtype'] = 'blog';
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
}