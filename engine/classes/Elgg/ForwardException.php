<?php

namespace Elgg;

/**
 * Exception thrown on forward() call
 *
 * If you catch this exception, re-throw it.
 */
class ForwardException extends \Exception {

	private $location;
	private $reason;

	/**
	 * Constructor
	 *
	 * @param string     $location URL to forward to browser to. This can be a path
	 *                             relative to the network's URL.
	 * @param string     $reason   Short explanation for why we're forwarding. Set to '404' to forward
	 *                             to error page. Default message is 'system'.
	 * @param \Exception $previous The previous exception
	 */
	public function __construct($location = "", $reason = 'system', \Exception $previous = null) {
		$this->location = $location;
		$this->reason = $reason;

		parent::__construct("The system has issued a forward() call.", 0, $previous);
	}

	/**
	 * Get the URL requested
	 *
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Get the reason stated for the redirect
	 *
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * Redirect the browser based on the data within this exception
	 *
	 * @return void
	 * @throws \SecurityException
	 */
	public function performRedirect() {
		_elgg_services()->forwarder->handleException($this);
	}
}
