<?php

namespace Elgg\Application;

use DateTime;
use Elgg\Application;
use Elgg\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * File server handler
 *
 * @access private
 *
 * @package Elgg.Core
 */
class ServeFileHandler {

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
		if (!preg_match('~serve-file/e(\d+)/l(\d+)/d([ia])/c([01])/([a-zA-Z0-9\-_]+)/(.*)$~', $path, $m)) {
			return $response->setStatusCode(400)->setContent('Malformatted request URL');
		}

		list(, $expires, $last_updated, $disposition, $use_cookie, $mac, $path_from_dataroot) = $m;

		if ($expires && $expires < time()) {
			return $response->setStatusCode(403)->setContent('URL has expired');
		}

		$etag = '"' . $last_updated . '"';
		$response->setPublic()->setEtag($etag);
		if ($response->isNotModified($request)) {
			return $response;
		}

		// @todo: change to minimal boot without plugins
		$this->application->bootCore();

		$hmac_data = array(
			'expires' => (int) $expires,
			'last_updated' => (int) $last_updated,
			'disposition' => $disposition,
			'path' => $path_from_dataroot,
			'use_cookie' => (int) $use_cookie,
		);
		if ((bool) $use_cookie) {
			$hmac_data['cookie'] = _elgg_services()->session->getId();
		}
		ksort($hmac_data);

		$hmac = elgg_build_hmac($hmac_data);
		if (!$hmac->matchesToken($mac)) {
			return $response->setStatusCode(403)->setContent('HMAC mistmatch');
		}

		$dataroot = _elgg_services()->config->getDataPath();
		$filenameonfilestore = "{$dataroot}{$path_from_dataroot}";

		if (!is_readable($filenameonfilestore)) {
			return $response->setStatusCode(404)->setContent('File not found');
		}

		$actual_last_updated = filemtime($filenameonfilestore);
		if ($actual_last_updated != $last_updated) {
			return $response->setStatusCode(403)->setContent('URL has expired');
		}

		$public = $use_cookie ? false : true;
		$content_disposition = $disposition == 'i' ? 'inline' : 'attachment';

		$response = new BinaryFileResponse($filenameonfilestore, 200, array(), $public, $content_disposition);
		$response->prepare($request);

		if (empty($expires)) {
			$expires = strtotime('+1 year');
		}
		$expires_dt = (new DateTime())->setTimestamp($expires);
		$response->setExpires($expires_dt);

		$response->setEtag($etag);
		return $response;
	}

}
