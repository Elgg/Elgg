<?php

class ElggTravisInstallTest extends ElggCoreUnitTest {

	public function setUp() {
		if (!getenv('TRAVIS')) {
			$this->skipIf(true, "Not Travis VM");
		}
	}

	public function testDbWasInstalled() {
		_elgg_services()->db->assertInstalled();
	}

	public function testThemePluginsAreLast() {
		$plugins = elgg_get_plugins('all');
		$plugins = array_reverse($plugins);
		/* @var ElggPlugin[] $plugins */

		$found_non_theme = false;
		foreach ($plugins as $i => $plugin) {
			$is_theme = in_array('theme', $plugin->getManifest()->getCategories());

			if ($found_non_theme) {
				$this->assertFalse($is_theme, 'All themes come last');
			} else {
				if ($i === 0) {
					$this->assertTrue($is_theme, 'Last plugin is a theme');
				}
				if (!$is_theme) {
					$found_non_theme = true;
				}
			}
		}
	}
}
