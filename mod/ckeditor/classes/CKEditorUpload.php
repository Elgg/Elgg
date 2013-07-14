<?php

/**
 * Upload container class
 *
 * @property string $filePath File path for the upload relative to the user's data dir
 * @access private
 */
class CKEditorUpload extends ElggObject {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "ckeditor_upload";
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getURL() {
		$user_guid = $this->getOwnerGUID();
		$basename = pathinfo($this->filePath, PATHINFO_BASENAME);
		$url = "uploads/images/$user_guid/$this->guid/$basename";
		return elgg_normalize_url($url);
	}


	/**
	 * {@inheritdoc}
	 */
	public function delete() {
		$userDir = new Elgg_EntityDirLocator($this->getOwnerGUID());
		$userDir = rtrim($userDir, DIRECTORY_SEPARATOR);
		$filePath = elgg_get_data_path() . $userDir . DIRECTORY_SEPARATOR . $this->filePath;
		unlink($filePath);
		return parent::delete();
	}
}
