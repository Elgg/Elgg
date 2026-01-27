<?php

namespace Elgg\ExternalPages;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 7.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritdoc}
	 */
	public function ready() {
		$external_pages = \ElggExternalPage::getAllowedPageNames();
		foreach ($external_pages as $page) {
			elgg_register_route("view:object:external_page:{$page}", [
				'path' => "/{$page}",
				'resource' => 'external_pages',
				'defaults' => [
					'page' => $page,
				],
				'walled' => false,
			]);
		}
	}
}
