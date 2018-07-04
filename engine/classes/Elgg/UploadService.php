<?php

namespace Elgg;

use Elgg\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Elgg\Filesystem\MimeTypeDetector;
use Elgg\ImageService;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since 2.3
 */
class UploadService {

	const FIXABLE = [
		'image/jpeg',
		'image/jpg',
		'image/tiff',
	];

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var ImageService
	 */
	private $images;

	/**
	 * Constructor
	 *
	 * @param Request      $request Http request
	 * @param ImageService $images  The image service
	 */
	public function __construct(Request $request, ImageService $images) {
		$this->request = $request;
		$this->images = $images;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name      Form input name
	 * @param bool   $fix_orientation Attempt to fix image orientation
	 *
	 * @return UploadedFile[]
	 */
	public function getFiles($input_name, $fix_orientation = true) {
		$files = $this->request->getFiles($input_name);

		foreach ($files as $file) {
			if ($file instanceof UploadedFile && $fix_orientation) {
				$this->fixImageOrientation($file);
			}
		}
		
		return $files;
	}

	/**
	 * Returns an single valid uploaded file object
	 *
	 * @param string $input_name         Form input name
	 * @param bool   $check_for_validity If there is an uploaded file, is it required to be valid
	 * @param bool   $fix_orientation    Attempt to fix image orientation
	 *
	 * @return UploadedFile[]|false
	 */
	public function getFile($input_name, $check_for_validity = true, $fix_orientation = true) {
		$file = $this->request->getFile($input_name, $check_for_validity);
		
		if ($file instanceof UploadedFile && $fix_orientation) {
			$this->fixImageOrientation($file);
		}
		
		return $file;
	}
	
	/**
	 * Fixes the orientation of an image
	 *
	 * @param UploadedFile $file File to fix
	 *
	 * @return void
	 */
	protected function fixImageOrientation(UploadedFile $file) {
		if (!$file->isValid()) {
			return;
		}

		$mime_detector = new MimeTypeDetector();
		$mime = $mime_detector->getType($file->getPathname());

		 if (!in_array($mime, self::FIXABLE)) {
		 	return;
		 }

		$temp_location = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid() . $file->getClientOriginalName();
		copy($file->getPathname(), $temp_location);
		
		$rotated = $this->images->fixOrientation($temp_location);
		if ($rotated) {
			copy($temp_location, $file->getPathname());
		}
	}
}
