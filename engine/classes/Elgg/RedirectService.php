<?php

namespace Elgg;

/**
 * Handles common tasks when redirecting a request
 */
class RedirectService {

	/**
	 * @var bool
	 */
	protected $is_xhr;

	/**
	 * @var string
	 */
	protected $site_url;

	/**
	 * @var string
	 */
	protected $current_url;

	/**
	 * Constructor
	 *
	 * @param bool   $is_xhr      Is the request from Ajax?
	 * @param string $site_url    Site URL
	 * @param string $current_url Current URL
	 */
	public function __construct($is_xhr, $site_url, $current_url) {
		$this->is_xhr = $is_xhr;
		$this->site_url = $site_url;
		$this->current_url = $current_url;
	}

	/**
	 * Capture the URL the user requested when they were redirected
	 *
	 * @return void
	 */
	public function setLastForwardFrom() {
		if ($this->is_xhr) {
			return;
		}

		if (0 !== strpos($this->current_url, $this->site_url)) {
			return;
		}
		$path = substr($this->current_url, strlen($this->site_url));

		$patterns = [
			'~^action/~',
			'~^cache/~',
			'~^serve-file/~',
		];
		foreach ($patterns as $pattern) {
			if (preg_match($pattern, $path)) {
				return;
			}
		}

		elgg_get_session()->set('last_forward_from', $this->current_url);
	}
}
