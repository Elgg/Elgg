<?php
namespace Elgg\Http;

use ElggSite;

/**
 * Overview: http://html5doctor.com/web-manifest-specification/
 * Spec: https://w3c.github.io/manifest/
 *
 * Support was added to Chrome 39 and is expected to come to Firefox soon.
 *
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.10
 *
 * @access private
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
		$head = _elgg_views_prepare_head($title);
		
		$icons = [];
		foreach ($head['links'] as $link) {
			if ($link['rel'] == 'icon') {
				$icons[] = [
					'sizes' => $link['sizes'],
					'src' => $link['href'],
					'type' => $link['type'],
				];
			}
		}
		
		return [
			'display' => 'standalone',
			'name' => $this->site->getDisplayName(),
			'start_url' => $this->site->getUrl(),
			'icons' => $icons,
		];
	}
}