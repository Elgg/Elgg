<?php

namespace Elgg\Http;

use ElggSite;

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
	/** @var ElggSite */
	private $site;
	
	/**
	 * Constructor
	 *
	 * @param ElggSite $site The site serving this manifest.
	 */
	public function __construct(ElggSite $site) {
		$this->site = $site;
	}
	
	/**
	 * Behavior for HTTP GET method
	 *
	 * @return array
	 */
	public function get() {
		return [
			'display' => 'standalone',
			'name' => $this->site->getDisplayName(),
			'start_url' => $this->site->getUrl(),
		];
	}
}
