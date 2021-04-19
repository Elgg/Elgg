<?php

namespace Elgg\Pages;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		// Language short codes must be of the form "pages:key"
		// where key is the array key below
		elgg_set_config('pages', [
			'title' => 'text',
			'description' => 'longtext',
			'tags' => 'tags',
			'parent_guid' => 'pages/parent',
			'access_id' => 'access',
			'write_access_id' => 'access',
		]);
	}
}
