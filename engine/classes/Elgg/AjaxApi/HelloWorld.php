<?php
namespace Elgg\AjaxApi;

use Elgg\Application;
use Elgg\Services\AjaxApi\ApiResponse;

/**
 * Just an example for reviewers
 *
 * @todo Remove this before merge
 */
class HelloWorld {

	/**
	 * Handle the API request
	 *
	 * @param ApiResponse $response API response
	 * @param Application $elgg     Elgg app
	 *
	 * @return ApiResponse
	 */
	function __invoke(ApiResponse $response, Application $elgg) {
		$name = get_input('name', 'Dave');

		system_message("How are you doing, $name?");

		return $response->setData([
			'name' => $name,
		]);
	}
}
