<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\OkResponse;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Elgg\Traits\TimeUsing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Controller to handle /serve-icon requests
 *
 * @since 4.0
 * @internal
 */
class ServeIcon {
	
	use TimeUsing;
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public function __invoke(Request $request) {
		
		// clear cache-boosting headers set by PHP session
		if (!isset($request->getHttpRequest()->_integration_testing)) {
			header_remove('Cache-Control');
			header_remove('Pragma');
			header_remove('Expires');
		}
		
		$guid = (int) $request->getParam('guid');
		$size = $request->getParam('size');
		
		$entity = get_entity($guid);
		if (!$entity instanceof \ElggEntity) {
			throw new EntityNotFoundException();
		}
		
		$thumbnail = $entity->getIcon($size);
		if (!$thumbnail->exists()) {
			throw new EntityNotFoundException('Icon does not exist');
		}
		
		$if_none_match = $request->getHttpRequest()->headers->get('if_none_match');
		if (!empty($if_none_match)) {
			// strip mod_deflate suffixes
			$request->getHttpRequest()->headers->set('if_none_match', str_replace('-gzip', '', $if_none_match));
		}
		
		$etag = '"' . $thumbnail->getModifiedTime(). '"';
		
		$response = new Response();
		$response->setExpires($this->getCurrentTime('-1 day'))
			->prepare($request->getHttpRequest())
			->setPrivate()
			->setEtag($etag)
			->setExpires($this->getCurrentTime('+1 day'))
			->setMaxAge(86400);
		
		if ($response->isNotModified($request->getHttpRequest())) {
			return new OkResponse('', ELGG_HTTP_NOT_MODIFIED);
		}
		
		$headers = [
			'Content-Type' => $thumbnail->getMimeType(),
			'X-Content-Type-Options' => 'nosniff',
		];
		$response = new BinaryFileResponse($thumbnail->getFilenameOnFilestore(), ELGG_HTTP_OK, $headers, false, 'inline');
		$response->prepare($request->getHttpRequest());
		
		$response->setPrivate()
			->setEtag($etag)
			->setExpires($this->getCurrentTime('+1 day'))
			->setMaxAge(86400);
		
		if (!$response->headers->hasCacheControlDirective('no-cache')) {
			$response->headers->addCacheControlDirective('no-cache', 'Set-Cookie');
		}
		
		$response->send();
		
		return elgg_ok_response();
	}
}
