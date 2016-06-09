<?php

namespace Elgg;

use Elgg\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since 2.3
 */
class UploadService {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * Constructor
	 *
	 * @param Request $request Http request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 * @return UploadedFile[]
	 */
	public function getUploadedFiles($input_name) {
		$files = $this->request->files;
		if (!$files->has($input_name)) {
			return false;
		}

		$uploaded_files = $files->get($input_name);
		if ($uploaded_files instanceof SymfonyUploadedFile) {
			$uploaded_files = array($uploaded_files);
		}

		$wrapped_files = [];
		if (empty($uploaded_files)) {
			return $wrapped_files;
		}

		foreach ($uploaded_files as $index => $uploaded_file) {
			/* @var $uploaded_file SymfonyUploadedFile */

			$path = $uploaded_file->getPathname();
			$name = $uploaded_file->getClientOriginalName();
			$mime_type = $uploaded_file->getClientMimeType();
			$size = $uploaded_file->getClientSize();
			$error = $uploaded_file->getError();

			$wrapped_files[$index] = new UploadedFile($path, $name, $mime_type, $size, $error);
		}
		
		return $wrapped_files;
	}

}
