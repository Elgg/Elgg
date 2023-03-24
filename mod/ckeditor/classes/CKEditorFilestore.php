<?php

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;

/**
 * CKEditor filestore
 *
 * @since 5.0
 */
class CKEditorFilestore extends \Elgg\Filesystem\Filestore\DiskFilestore {
	
	/**
	 * Number of entries per matrix dir.
	 * You almost certainly don't want to change this.
	 */
	const BUCKET_SIZE = 500;
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function getFilenameOnFilestore(\ElggFile $file): string {
		
		$owner_guid = $file->getOwnerGuid() ?: _elgg_services()->session_manager->getLoggedInUserGuid();
		if (!$owner_guid) {
			throw new ElggInvalidArgumentException("File {$file->getFilename()} is missing an owner!");
		}
		
		$filename = $file->getFilename();
		if (empty($filename)) {
			throw new ElggInvalidArgumentException("File {$file->getFilename()} is missing a filename!");
		}
		
		// Windows has different separators
		$filename = str_ireplace(DIRECTORY_SEPARATOR, '/', $filename);

		$trim = function($value) {
			return rtrim($value, '/\\');
		};
		$parts = array_map($trim, [
			$this->getUploadPath($owner_guid),
			$filename,
		]);
		
		$dirroot = elgg_extract('dir_root', $this->getParameters(), '');
		
		return $dirroot . implode('/', $parts);
	}
	
	/**
	 * Make the correct folder structure for an owner
	 *
	 * @param int $owner_guid the owner to generate for
	 *
	 * @return string
	 */
	protected function getUploadPath(int $owner_guid): string {
		$lower_bound = (int) max(floor($owner_guid / self::BUCKET_SIZE) * self::BUCKET_SIZE, 1);
		
		return implode('/', [
			'editor_images',
			$lower_bound,
			$owner_guid,
		]);
	}
}
