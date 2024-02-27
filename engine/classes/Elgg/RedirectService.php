<?php
namespace Elgg;

/**
 * Handles common tasks when redirecting a request
 */
class RedirectService {

	protected bool $is_xhr;

	protected string $site_url;

	protected string $current_url;

	/**
	 * Constructor
	 *
	 * @param \ElggSession       $session Elgg session
	 * @param \Elgg\Http\Request $request Request
	 * @param \Elgg\Config       $config  Configuration
	 */
	public function __construct(
		protected \ElggSession $session,
		\Elgg\Http\Request $request,
		\Elgg\Config $config
	) {
		$this->is_xhr = $request->isXmlHttpRequest();
		$this->site_url = $config->wwwroot;
		$this->current_url = $request->getCurrentURL();
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

		if (elgg_strpos($this->current_url, $this->site_url) !== 0) {
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
