<?php

namespace Elgg\WebServices\Middleware;

use Elgg\Request;
use Elgg\Exceptions\Http\BadRequestException;

/**
 * Set correct view_type for api calls
 *
 * @since 4.0
 */
class ViewtypeMiddleware {
	
	/**
	 * Invoke middleware
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return void
	 * @throws BadRequestException
	 */
	public function __invoke(Request $request) {
		
		$viewtype = $request->getParam('view', 'json');
		if (!ctype_alpha($viewtype)) {
			throw new BadRequestException('Invalid format');
		}
		
		elgg_set_viewtype($viewtype);
	}
}
