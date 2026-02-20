<?php

namespace Elgg\Assets;

class ExternalFilesServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	public function up() {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'subresource_integrity_enabled' => true,
				'system_cache_enabled' => true,
				'simplecache_enabled' => true,
			],
		]);
		
		_elgg_services()->events->backup();
		_elgg_services()->events->registerHandler('simplecache:generate', 'css', \Elgg\Views\CalculateSRI::class);
		
		// clear cache used by SRI
		_elgg_services()->serverCache->delete('sri');
	}

	public function down() {
		_elgg_services()->events->restore();
	}
	
	public function testSRICalculate() {
		
		$files = _elgg_services()->externalFiles;
		
		$content = '.foo { color:red; }';
		$expected_hash = 'sha256-' . base64_encode(hash('sha256', $content, true));
		elgg_trigger_event_results('simplecache:generate', 'css', [
			'view' => 'foo.css',
		], $content);
		
		$files->register('css', 'foo', elgg_get_simplecache_url('foo.css'), 'head');
		$files->load('css', 'foo');
		
		$loaded = $files->getLoadedResources('css', 'head');
		$this->assertArrayHasKey('foo', $loaded);
		$this->assertEquals($expected_hash, $loaded['foo']->integrity);
	}
}
