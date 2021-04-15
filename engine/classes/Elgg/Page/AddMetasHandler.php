<?php

namespace Elgg\Page;

/**
 * Add metas to page head
 *
 * @since 4.0
 */
class AddMetasHandler {
	
	/**
	 * Add metas to HTML head
	 *
	 * @param \Elgg\Hook $hook 'head', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$head_params = $hook->getValue();
		
		$head_params['metas']['content-type'] = [
			'http-equiv' => 'Content-Type',
			'content' => 'text/html; charset=utf-8',
		];
		
		$head_params['metas']['description'] = [
			'name' => 'description',
			'content' => _elgg_services()->config->sitedescription
		];
		
		// https://developer.chrome.com/multidevice/android/installtohomescreen
		$head_params['metas']['viewport'] = [
			'name' => 'viewport',
			'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0',
		];
		
		$head_params['metas']['mobile-web-app-capable'] = [
			'name' => 'mobile-web-app-capable',
			'content' => 'yes',
		];
		
		$head_params['metas']['apple-mobile-web-app-capable'] = [
			'name' => 'apple-mobile-web-app-capable',
			'content' => 'yes',
		];
	
		return $head_params;
	}
}
