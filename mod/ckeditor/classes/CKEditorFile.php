<?php

/**
 * A CKEditor uploaded file
 *
 * @since 5.0
 */
class CKEditorFile extends \ElggFile {
	
	protected ?\Elgg\Filesystem\Filestore\DiskFilestore $fs;
	
	/**
	 * {@inheritdoc}
	 */
	protected function getFilestore(): \Elgg\Filesystem\Filestore\DiskFilestore {
		if (!isset($this->fs)) {
			 $this->fs = new CKEditorFilestore();
		}
		
		return $this->fs;
	}
}
