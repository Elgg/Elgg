<?php

namespace Elgg;

/**
 * Service for handling forward exceptions
 *
 * @access private
 */
class Forwarder {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	private $url;
	private $referrer;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks       Hooks service
	 * @param string             $current_url The current URL
	 * @param string|null        $referrer    The HTTP Referer header
	 */
	public function __construct(PluginHooksService $hooks, $current_url, $referrer = null) {
		$this->hooks = $hooks;
		$this->url = $current_url;
		$this->referrer = $referrer;
	}

	/**
	 * Handle the forward exception
	 *
	 * @param ForwardException $exception The exception
	 * @return void
	 * @throws \SecurityException
	 */
	public function handleException(ForwardException $exception) {
		$location = $exception->getLocation();
		$reason = $exception->getReason();

		if (headers_sent($file, $line)) {
			$msg = "Redirect could not be issued due to headers already being sent. Halting execution for security. "
				. "Output started in file $file at line $line. Search http://learn.elgg.org/ for more information.";
			throw new \SecurityException($msg, 0, $exception);
		}

		if ($location === REFERER) {
			$location = $this->referrer;
		}

		$location = elgg_normalize_url($location);

		// return new forward location or false to stop the forward or empty string to exit
		$params = array('current_url' => $this->url, 'forward_url' => $location);
		$location = $this->hooks->trigger('forward', $reason, $params, $location);

		if ($location) {
			header("Location: {$location}");
		}
		exit;
	}
}
