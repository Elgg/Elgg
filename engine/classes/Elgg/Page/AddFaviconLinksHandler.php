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
	 * @param \Elgg\Event $event 'head', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$head_params = $event->getValue();
		
		if (elgg_get_site_entity()->hasIcon('master', 'favicon')) {
			$config = $this->getSiteIcons();
		} else {
			$config = $this->getDefaultIcons();
		}
		
		foreach ($config as $name => $link) {
			$head_params['links'][$name] = $link;
		}
		
		return $head_params;
	}
	
	/**
	 * Returns default icons
	 *
	 * @return array
	 */
	protected function getDefaultIcons(): array {
		return [
			'apple-touch-icon' => [
				'rel' => 'apple-touch-icon',
				'type' => 'image/png',
				'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
			],
			'icon-ico' => [
				'rel' => 'icon',
				'href' => elgg_get_simplecache_url('graphics/favicon.ico'),
			],
			'icon-16' => [
				'rel' => 'icon',
				'sizes' => '16x16',
				'type' => 'image/png',
				'href' => elgg_get_simplecache_url('graphics/favicon-16.png'),
			],
			'icon-32' => [
				'rel' => 'icon',
				'sizes' => '32x32',
				'type' => 'image/png',
				'href' => elgg_get_simplecache_url('graphics/favicon-32.png'),
			],
			'icon-64' => [
				'rel' => 'icon',
				'sizes' => '64x64',
				'type' => 'image/png',
				'href' => elgg_get_simplecache_url('graphics/favicon-64.png'),
			],
			'icon-128' => [
				'rel' => 'icon',
				'sizes' => '128x128',
				'type' => 'image/png',
				'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
			],
		];
	}
	
	/**
	 * Returns icons for the site entity
	 *
	 * @return array
	 */
	protected function getSiteIcons(): array {
		$site = elgg_get_site_entity();
		
		$sizes = elgg_get_icon_sizes('site', null, 'favicon');
		unset($sizes['master']);
		
		$result = [];
		
		if (isset($sizes['icon-128'])) {
			$result['apple-touch-icon'] = [
				'rel' => 'apple-touch-icon',
				'type' => 'image/jpeg',
				'href' => $site->getIconURL(['size' => 'icon-128', 'type' => 'favicon']),
			];
		}

		if (isset($sizes['icon-32'])) {
			$result['icon-ico'] = [
				'rel' => 'icon',
				'href' => $site->getIconURL(['size' => 'icon-32', 'type' => 'favicon']),
			];
		}
		
		foreach ($sizes as $name => $size_config) {
			if (!elgg_extract('square', $size_config, false)) {
				continue;
			}
			
			$result[$name] = [
				'rel' => 'icon',
				'type' => 'image/jpeg',
				'sizes' => "{$size_config['w']}x{$size_config['h']}",
				'href' => $site->getIconURL(['size' => $name, 'type' => 'favicon']),
			];
		}
		
		return $result;
	}
}
