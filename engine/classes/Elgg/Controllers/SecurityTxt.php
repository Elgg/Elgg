<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\ResponseBuilder;
use Elgg\Values;

/**
 * Controller for the /security.txt resource
 *
 * @since 5.1
 */
class SecurityTxt {
	
	/**
	 * Handle the request
	 *
	 * @param \Elgg\Request $request current request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public function __invoke(\Elgg\Request $request): ResponseBuilder {
		$contact = elgg_get_config('security_txt_contact');
		$expires = elgg_get_config('security_txt_expires');
		if (empty($contact) || empty($expires)) {
			throw new EntityNotFoundException();
		}
		
		$lines = [
			"Contact: {$contact}",
			'Expires: ' . Values::normalizeTime($expires)->format(DATE_ATOM),
		];
		
		$fields = [
			'encryption' => 'Encryption',
			'acknowledgments' => 'Acknowledgments',
			'language' => 'Preferred-Languages',
			'canonical' => 'Canonical',
			'policy' => 'Policy',
			'hiring' => 'Hiring',
			'csaf' => 'CSAF',
		];
		foreach ($fields as $name => $output) {
			$value = elgg_get_config("security_txt_{$name}");
			if (empty($value)) {
				continue;
			}
			
			$lines[] = "{$output}: {$value}";
		}
		
		$response = elgg_ok_response(implode(PHP_EOL, $lines));
		$response->setHeaders([
			'Content-Type' => 'text/plain; charset=utf-8',
		]);
		
		return $response;
	}
}
