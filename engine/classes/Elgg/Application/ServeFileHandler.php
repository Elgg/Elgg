<?php

namespace Elgg\Application;

use DateTime;
use Elgg\Application;
use Elgg\Config;
use Elgg\Filesystem\MimeTypeDetector;
use Elgg\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Elgg\Security\Base64Url;
use Elgg\Security\HmacFactory;

/**
 * File server handler
 *
 * @access private
 *
 * @package Elgg.Core
 */
class ServeFileHandler {

	/**
	 * @var HmacFactory
	 */
	private $hmac;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param HmacFactory $hmac   HMAC service
	 * @param Config      $config Config service
	 */
	public function __construct(HmacFactory $hmac, Config $config) {
		$this->hmac = $hmac;
		$this->config = $config;
	}

	/**
	 * Handle a request for a file
	 *
	 * @param Request $request HTTP request
	 * @return Response
	 */
	public function getResponse(Request $request) {

		$response = new Response();
		$response->prepare($request);

		$path = implode('/', $request->getUrlSegments(true));
		if (!preg_match('~serve-file/e(\d+)/l(\d+)/d([ia])/c([01])/([a-zA-Z0-9\-_]+)/(.*)$~', $path, $m)) {
			return $response->setStatusCode(400)->setContent('Malformatted request URL');
		}

		list(, $expires, $last_updated, $disposition, $use_cookie, $mac, $path_from_dataroot) = $m;

		if ($expires && $expires < time()) {
			return $response->setStatusCode(403)->setContent('URL has expired');
		}
		
		// revert spaces from elgg_normalize_url()
		// not using urldecode because it could replace to much
		$path_from_dataroot = str_replace('%20', ' ', $path_from_dataroot);

		$hmac_data = [
			'expires' => (int) $expires,
			'last_updated' => (int) $last_updated,
			'disposition' => $disposition,
			'path' => $path_from_dataroot,
			'use_cookie' => (int) $use_cookie,
		];
		if ((bool) $use_cookie) {
			$hmac_data['cookie'] = $this->getCookieValue($request);
		}
		ksort($hmac_data);

		$hmac = $this->hmac->getHmac($hmac_data);
		if (!$hmac->matchesToken($mac)) {
			return $response->setStatusCode(403)->setContent('HMAC mismatch');
		}

		// Path may have been encoded to avoid problems with special chars in URLs
		if (0 === strpos($path_from_dataroot, ':')) {
			$path_from_dataroot = Base64Url::decode(substr($path_from_dataroot, 1));
		}

		$filenameonfilestore = "{$this->config->dataroot}{$path_from_dataroot}";

		if (!is_readable($filenameonfilestore)) {
			return $response->setStatusCode(404)->setContent('File not found');
		}

		$actual_last_updated = filemtime($filenameonfilestore);
		if ($actual_last_updated != $last_updated) {
			return $response->setStatusCode(403)->setContent('URL has expired');
		}

		$if_none_match = $request->headers->get('if_none_match');
		if (!empty($if_none_match)) {
			// strip mod_deflate suffixes
			$request->headers->set('if_none_match', str_replace('-gzip', '', $if_none_match));
		}

		$etag = '"' . $actual_last_updated . '"';
		$response->setPublic()->setEtag($etag);
		if ($response->isNotModified($request)) {
			return $response;
		}

		$public = $use_cookie ? false : true;
		$content_disposition = $disposition == 'i' ? 'inline' : 'attachment';

		$headers = [
			'Content-Type' => (new MimeTypeDetector())->getType($filenameonfilestore),
		];
		$response = new BinaryFileResponse($filenameonfilestore, 200, $headers, $public, $content_disposition);
		
		$sendfile_type = $this->config->x_sendfile_type;
		if ($sendfile_type) {
			$request->headers->set('X-Sendfile-Type', $sendfile_type);

			$mapping = (string) $this->config->x_accel_mapping;
			$request->headers->set('X-Accel-Mapping', $mapping);

			$response->trustXSendfileTypeHeader();
		}
		
		$response->prepare($request);

		if (empty($expires)) {
			$expires = strtotime('+1 year');
		}
		$expires_dt = (new DateTime())->setTimestamp($expires);
		$response->setExpires($expires_dt);

		$response->setEtag($etag);
		return $response;
	}

	/**
	 * Get the session ID from the cookie
	 *
	 * @param Request $request Elgg request
	 * @return string
	 */
	private function getCookieValue(Request $request) {
		$config = $this->config->getCookieConfig();
		$session_name = $config['session']['name'];
		return $request->cookies->get($session_name, '');
	}
}
