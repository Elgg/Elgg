<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\CronException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Controller to handle /cron requests
 *
 * @since 4.0
 * @internal
 */
class Cron {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		
		if (_elgg_services()->config->security_protect_cron) {
			elgg_signed_request_gatekeeper();
		}
		
		$segments = explode('/', trim($request->getParam('segments'), '/'));
		
		$interval = elgg_strtolower(array_shift($segments));
		
		$intervals = null;
		if ($interval !== 'run') {
			$intervals = [$interval];
		}
		
		$output = '';
		try {
			$force = (bool) $request->getParam('force');
			$jobs = _elgg_services()->cron->run($intervals, $force);
			foreach ($jobs as $job) {
				$output .= $job->getOutput() . PHP_EOL;
			}
		} catch (CronException $ex) {
			$output .= "Exception: {$ex->getMessage()}";
		}
		
		return elgg_ok_response(nl2br($output));
	}
}
