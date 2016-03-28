<?php

namespace Elgg\Application;

use DateTime;
use Elgg\Application;
use Elgg\Http\Request;
use ElggFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Elgg\Filesystem\MimeTypeDetector;

/**
 * File download handler for files with custom filestore
 *
 * @access private
 *
 * @package Elgg.Core
 */
class DownloadFileHandler {

	/** @var Application */
	private $application;

	/**
	 * Constructor
	 *
	 * @param Application $app Elgg Application
	 */
	public function __construct(Application $app) {
		$this->application = $app;
	}

	/**
	 * Handle a request for a file
	 *
	 * @param Request $request HTTP request
	 * @return Response
	 */
	public function getResponse($request) {

		$response = new Response();
		$response->prepare($request);

		$path = implode('/', $request->getUrlSegments());
		if (!preg_match('~download-file/g(\d+)$~', $path, $m)) {
			return $response->setStatusCode(400)->setContent('Malformatted request URL');
		}

		$this->application->start();

		$guid = (int) $m[1];
		$file = get_entity($guid);

		if (!$file instanceof ElggFile) {
			return $response->setStatusCode(404)->setContent("File with guid $guid does not exist");
		}

		$filenameonfilestore = $file->getFilenameOnFilestore();

		if (!is_readable($filenameonfilestore)) {
			return $response->setStatusCode(404)->setContent('File not found');
		}

		$last_updated = filemtime($filenameonfilestore);
		$etag = '"' . $last_updated . '"';
		$response->setPublic()->setEtag($etag);
		if ($response->isNotModified($request)) {
			return $response;
		}

		$headers = [
			'Content-Type' => (new MimeTypeDetector())->getType($filenameonfilestore),
		];
		$response = new BinaryFileResponse($filenameonfilestore, 200, $headers, false, 'attachment');
		$response->prepare($request);

		$expires = strtotime('+1 year');
		$expires_dt = (new DateTime())->setTimestamp($expires);
		$response->setExpires($expires_dt);

		$response->setEtag($etag);
		return $response;
	}

}
