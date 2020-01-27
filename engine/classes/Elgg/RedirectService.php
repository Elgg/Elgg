<?php
namespace Elgg;

use ElggSession;

/**
 * Handles common tasks when redirecting a request
 */
class RedirectService {

	/**
	 * @var ElggSession
	 */
	protected $session;

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
	 * @param ElggSession $session     Elgg session
	 * @param bool        $is_xhr      Is the request from Ajax?
	 * @param string      $site_url    Site URL
	 * @param string      $current_url Current URL
	 */
	public function __construct(ElggSession $session, $is_xhr, $site_url, $current_url) {
		$this->session = $session;
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

		if (0 !== elgg_strpos($this->current_url, $this->site_url)) {
			return;
		}
		$path = elgg_substr($this->current_url, elgg_strlen($this->site_url));

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

		$this->session->set('last_forward_from', $this->current_url);
	}
}
