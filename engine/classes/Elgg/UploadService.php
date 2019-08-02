<?php

namespace Elgg;

use Elgg\Http\Request as HttpRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @internal
 * @since 2.3
 */
class UploadService {

	/**
	 * @var \Elgg\Http\Request
	 */
	private $request;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Http\Request $request Http request
	 */
	public function __construct(HttpRequest $request) {
		$this->request = $request;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 *
	 * @return UploadedFile[]
	 */
	public function getFiles($input_name) {
		return $this->request->getFiles($input_name);
	}

	/**
	 * Returns an single valid uploaded file object
	 *
	 * @param string $input_name         Form input name
	 * @param bool   $check_for_validity If there is an uploaded file, is it required to be valid
	 *
	 * @return UploadedFile|false
	 */
	public function getFile($input_name, $check_for_validity = true) {
		return $this->request->getFile($input_name, $check_for_validity);
	}
}
