<?php

namespace Elgg\CKEditor;

use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Controller to handle /ckeditor/upload requests
 *
 * @since 5.0
 * @internal
 */
class Upload {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		$response = new \Elgg\Http\OkResponse();
		$response->setHeaders([
			'Content-Type' => 'application/json;charset=utf-8',
		]);
		
		$upload = elgg_get_uploaded_file('upload', false);
		if (!$upload instanceof UploadedFile) {
			return $response->setContent(['error' => ['message' => elgg_echo('ckeditor:upload:missing_upload')]]);
		}
		
		if (!$upload->isValid()) {
			return $response->setContent(['error' => ['message' => $upload->getErrorMessage()]]);
		}

		if (elgg()->mimetype->getSimpleType((string) $upload->getMimeType()) !== 'image') {
			return $response->setContent(['error' => ['message' => elgg_echo('ckeditor:upload:invalid_type')]]);
		}
		
		try {
			return $response->setContent(['url' => $this->getFileUrl($upload)]);
		} catch (\Exception $e) {
			return $response->setContent(['error' => ['message' => $e->getMessage()]]);
		}
	}
	
	/**
	 * Returns a file url for a uploaded file
	 *
	 * @param UploadedFile $upload the file to upload
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	protected function getFileUrl(UploadedFile $upload): string {
		
		$fh = new \CKEditorFile();
		$fh->owner_guid = elgg_get_logged_in_user_guid();
		$fh->setFilename(uniqid() . '.jpg');
		
		// touch file location in order to create the file
		$fh->open('write');
		$fh->close();
		
		// copy first as we can only rotate with a correct image extension
		$temp_file = elgg_get_temp_file();
		$temp_file->setFilename(basename($fh->getFilenameOnFilestore()));
		$temp_file->open('write');
		$temp_file->close();
		
		copy($upload->getPathname(), $temp_file->getFilenameOnFilestore());
		
		_elgg_services()->imageService->fixOrientation($temp_file->getFilenameOnFilestore());
		
		// resize image to save diskspace (2048x2048px)
		$success = elgg_save_resized_image($temp_file->getFilenameOnFilestore(), $fh->getFilenameOnFilestore(), [
			'w' => 2048,
			'h' => 2048,
			'square' => false,
			'upscale' => false,
		]);
		
		if (empty($success)) {
			// remove new file
			$fh->delete();
			
			// report error
			throw new \Exception(elgg_echo('ckeditor:upload:resize_failed'));
		}
		
		return elgg_get_inline_url($fh);
	}
}
