<?php

namespace Elgg\Page;

/**
 * Add favicon links to page head
 *
 * @since 4.0
 */
class AddFaviconLinksHandler {
	
	/**
	 * Add favicon link tags to HTML head
	 *
	 * @param \Elgg\Hook $hook 'head', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$head_params = $hook->getValue();
	
		$head_params['links']['apple-touch-icon'] = [
			'rel' => 'apple-touch-icon',
			'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
		];
	
		// favicons
		$head_params['links']['icon-ico'] = [
			'rel' => 'icon',
			'href' => elgg_get_simplecache_url('graphics/favicon.ico'),
		];
		$head_params['links']['icon-vector'] = [
			'rel' => 'icon',
			'sizes' => '16x16 32x32 48x48 64x64 128x128',
			'type' => 'image/svg+xml',
			'href' => elgg_get_simplecache_url('graphics/favicon.svg'),
		];
		$head_params['links']['icon-16'] = [
			'rel' => 'icon',
			'sizes' => '16x16',
			'type' => 'image/png',
			'href' => elgg_get_simplecache_url('graphics/favicon-16.png'),
		];
		$head_params['links']['icon-32'] = [
			'rel' => 'icon',
			'sizes' => '32x32',
			'type' => 'image/png',
			'href' => elgg_get_simplecache_url('graphics/favicon-32.png'),
		];
		$head_params['links']['icon-64'] = [
			'rel' => 'icon',
			'sizes' => '64x64',
			'type' => 'image/png',
			'href' => elgg_get_simplecache_url('graphics/favicon-64.png'),
		];
		$head_params['links']['icon-128'] = [
			'rel' => 'icon',
			'sizes' => '128x128',
			'type' => 'image/png',
			'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
		];
	
		return $head_params;
	}
}
