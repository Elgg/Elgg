<?php

namespace Elgg;

use Elgg\Http\Request;
use Elgg\Http\ResponseBuilder;
use RuntimeException;

/**
 * Delegates requests to controllers based on the registered configuration.
 *
 * Plugin devs should use these wrapper functions:
 *  * elgg_register_page_handler
 *  * elgg_unregister_page_handler
 *
 * @package    Elgg.Core
 * @subpackage Router
 * @since      1.9.0
 * @access private
 */
class Router {

	use Profilable;

	private $handlers = [];
	private $hooks;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks For customized routing.
	 */
	public function __construct(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $identifier` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param Request $request The request to handle.
	 * @return boolean Whether the request was routed successfully.
	 * @access private
	 */
	public function route(Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
			$segments = [];
		}

		$is_walled_garden = _elgg_services()->config->get('walled_garden');
		$is_logged_in = _elgg_services()->session->isLoggedIn();
		$url = elgg_normalize_url($identifier . '/' . implode('/', $segments));
		
		if ($is_walled_garden && !$is_logged_in && !$this->isPublicPage($url)) {
			if (!elgg_is_xhr()) {
				_elgg_services()->session->set('last_forward_from', current_page_url());
			}
			register_error(_elgg_services()->translator->translate('loggedinrequired'));
			_elgg_services()->responseFactory->redirect('', 'walled_garden');
			return false;
		}

		// return false to stop processing the request (because you handled it)
		// return a new $result array if you want to route the request differently
		$old = [
			'identifier' => $identifier,
			'handler' => $identifier, // backward compatibility
			'segments' => $segments,
		];

		if ($this->timer) {
			$this->timer->begin(['build page']);
		}

		ob_start();

		$result = $this->hooks->trigger('route', $identifier, $old, $old);
		if ($result === false) {
			$output = ob_get_clean();
			$response = elgg_ok_response($output);
		} else {
			if ($result !== $old) {
				_elgg_services()->logger->warn('Use the route:rewrite hook to modify routes.');
			}

			if ($identifier != $result['identifier']) {
				$identifier = $result['identifier'];
			} else if ($identifier != $result['handler']) {
				$identifier = $result['handler'];
			}

			$segments = $result['segments'];

			$response = false;

			if (isset($this->handlers[$identifier]) && is_callable($this->handlers[$identifier])) {
				$function = $this->handlers[$identifier];
				$response = call_user_func($function, $segments, $identifier);
			}
			
			$output = ob_get_clean();
			
			if ($response === false) {
				return headers_sent();
			}

			if (!$response instanceof ResponseBuilder) {
				$response = elgg_ok_response($output);
			}
		}

		if (_elgg_services()->responseFactory->getSentResponse()) {
			return true;
		}
		
		_elgg_services()->responseFactory->respond($response);
		return headers_sent();
	}

	/**
	 * Register a function that gets called when the first part of a URL is
	 * equal to the identifier.
	 *
	 * @param string $identifier The page type to handle
	 * @param string $function   Your function name
	 *
	 * @return bool Depending on success
	 */
	public function registerPageHandler($identifier, $function) {
		if (is_callable($function, true)) {
			$this->handlers[$identifier] = $function;
			return true;
		}

		return false;
	}

	/**
	 * Unregister a page handler for an identifier
	 *
	 * @param string $identifier The page type identifier
	 *
	 * @return void
	 */
	public function unregisterPageHandler($identifier) {
		unset($this->handlers[$identifier]);
	}

	/**
	 * Get page handlers as array of identifier => callback
	 *
	 * @return array
	 */
	public function getPageHandlers() {
		return $this->handlers;
	}

	/**
	 * Filter a request through the route:rewrite hook
	 *
	 * @param Request $request Elgg request
	 *
	 * @return Request
	 * @access private
	 */
	public function allowRewrite(Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
		}

		$old = [
			'identifier' => $identifier,
			'segments' => $segments,
		];
		$new = _elgg_services()->hooks->trigger('route:rewrite', $identifier, $old, $old);
		if ($new === $old) {
			return $request;
		}

		if (!isset($new['identifier']) || !isset($new['segments']) || !is_string($new['identifier']) || !is_array($new['segments'])
		) {
			throw new RuntimeException('rewrite_path handler returned invalid route data.');
		}

		// rewrite request
		$segments = $new['segments'];
		array_unshift($segments, $new['identifier']);
		return $request->setUrlSegments($segments);
	}

	/**
	 * Checks if the page should be allowed to be served in a walled garden mode
	 *
	 * Pages are registered to be public by {@elgg_plugin_hook public_pages walled_garden}.
	 *
	 * @param string $url Defaults to the current URL
	 *
	 * @return bool
	 * @since 3.0
	 */
	public function isPublicPage($url = '') {
		if (empty($url)) {
			$url = current_page_url();
		}

		$parts = parse_url($url);
		unset($parts['query']);
		unset($parts['fragment']);
		$url = elgg_http_build_url($parts);
		$url = rtrim($url, '/') . '/';

		$site_url = _elgg_services()->config->getSiteUrl();

		if ($url == $site_url) {
			// always allow index page
			return true;
		}

		// default public pages
		$defaults = [
			'walled_garden/.*',
			'action/.*',
			'login',
			'register',
			'forgotpassword',
			'changepassword',
			'refresh_token',
			'ajax/view/languages.js',
			'upgrade\.php',
			'css/.*',
			'js/.*',
			'cache/[0-9]+/\w+/.*',
			'cron/.*',
			'services/.*',
			'serve-file/.*',
			'robots.txt',
			'favicon.ico',
		];

		$params = [
			'url' => $url,
		];

		$public_routes = _elgg_services()->hooks->trigger('public_pages', 'walled_garden', $params, $defaults);
		
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
