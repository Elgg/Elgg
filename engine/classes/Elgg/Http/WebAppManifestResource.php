<?php

namespace Elgg\Http;

/**
 * Overview: http://html5doctor.com/web-manifest-specification/
 * Spec: https://w3c.github.io/manifest/
 *
 * Support was added to Chrome 39 and is expected to come to Firefox soon.
 *
 * @since 1.10
 *
 * @internal
 */
class WebAppManifestResource {

	/**
	 * Constructor
	 *
	 * @param \ElggSite $site The site serving this manifest.
	 */
	public function __construct(protected \ElggSite $site) {
	}
	
	/**
	 * Behavior for HTTP GET method
	 *
	 * @return array
	 */
	public function get(): array {
		return [
			'display' => 'standalone',
			'name' => $this->site->getDisplayName(),
			'start_url' => $this->site->getUrl(),
		];
	}
}
