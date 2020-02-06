<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\Gatekeeper\WalledGardenException;
use Elgg\Request;

/**
 * Protects a route from non-authenticated users in a walled garden mode
 */
class WalledGarden {

	/**
	 * Gatekeeper
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws WalledGardenException
	 */
	public function __invoke(Request $request) {
		if ($request->elgg()->session->isLoggedIn()) {
			return;
		}

		if (!$request->elgg()->config->walled_garden) {
			return;
		}

		$url = $request->getURL();

		if ($this->isPublicPage($url)) {
			return;
		}

		if (!$request->isXhr()) {
			$request->elgg()->session->set('last_forward_from', $url);
		}

		throw new WalledGardenException();
	}

	/**
	 * Checks if the page should be allowed to be served in a walled garden mode
	 *
	 * Pages are registered to be public by {@elgg_plugin_hook public_pages walled_garden}.
	 *
	 * @param string $url Defaults to the current URL
	 *
	 * @return bool
	 * @internal
	 */
	protected function isPublicPage($url = '') {
		if (empty($url)) {
			$url = current_page_url();
		}

		$parts = parse_url($url);
		unset($parts['query']);
		unset($parts['fragment']);
		$url = elgg_http_build_url($parts);
		$url = rtrim($url, '/') . '/';

		$site_url = elgg()->config->wwwroot;

		if ($url == $site_url) {
			// always allow index page
			return true;
		}

		// default public pages
		$defaults = [
			'ajax/view/languages.js',
			'css/.*',
			'js/.*',
			'cache/[0-9]+/\w+/.*',
			'serve-file/.*',
		];

		$params = [
			'url' => $url,
		];

		$public_routes = elgg()->hooks->trigger('public_pages', 'walled_garden', $params, $defaults);

		$site_url = preg_quote($site_url);
		foreach ($public_routes as $public_route) {
			$pattern = "`^{$site_url}{$public_route}/*$`i";
			if (preg_match($pattern, $url)) {
				return true;
			}
		}

		// non-public page
		return false;
	}
}
