<?php

namespace Elgg\WebServices\Middleware;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Request;

/**
 * Validate that the 'api/output' view is available in the requested output format
 *
 * @since 4.0
 */
class RestApiOutputMiddleware {
	
	/**
	 * Invoke middleware
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return void
	 * @throws BadRequestException
	 */
	public function __invoke(Request $request) {
		
		$viewtype = $request->getParam('view', elgg_get_viewtype());
		if (elgg_view_exists('api/output', $viewtype)) {
			return;
		}
		
		$message = elgg_echo('BadRequestException:MissingOutputViewInViewtype', [$viewtype]);
		if (in_array($viewtype, ['xml', 'php'])) {
			$message .= PHP_EOL . elgg_echo('BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin');
		}
		
		throw new BadRequestException($message);
	}
}
