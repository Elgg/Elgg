<?php

namespace Elgg\Plugins;

use Elgg\Plugin\Composer;
use Elgg\PluginsIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ComposerIntegrationTest extends PluginsIntegrationTestCase {
	
	/**
	 * Get the Composer helper class
	 *
	 * @param \ElggPlugin $plugin the plugin to get it for
	 *
	 * @return Composer
	 * @throws \Elgg\Exceptions\Plugin\ComposerException
	 */
	protected function getComposer(\ElggPlugin $plugin): Composer {
		return new Composer($plugin);
	}

	#[DataProvider('activePluginsProvider')]
	public function testAssertPluginId(\ElggPlugin $plugin) {
		$composer = $this->getComposer($plugin);
		
		$composer->assertPluginId();
	}
}
