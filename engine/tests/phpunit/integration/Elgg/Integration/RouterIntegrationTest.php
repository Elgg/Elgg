<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

class RouterIntegrationTest extends IntegrationTestCase {

	public function up() {
		
	}
	
	public function down() {
		
	}
	
	/**
	 * @dataProvider urlProvider
	 */
	public function testUrlGenerationNormalization($site_url) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'wwwroot' => $site_url,
			],
		]);
		
		$this->assertNotEmpty(elgg_get_site_url());
		$this->assertEquals($site_url, elgg_get_site_url());
		
		elgg_register_route('foo:bar', [
			'path' => '/foo/bar/test',
			'handler' => function () {}
		]);
		
		$url = elgg_generate_url('foo:bar');
		
		// test if url is normalized
		$this->assertStringStartsWith(elgg_get_site_url(), $url);
	}

	/**
	 * @dataProvider urlProvider
	 */
	public function testUrlGenerationSchemeCorrection($site_url) {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'wwwroot' => $site_url,
			],
		]);
		
		$this->assertNotEmpty(elgg_get_site_url());
		$this->assertEquals($site_url, elgg_get_site_url());
		
		_elgg_services()->requestContext->setScheme('https');
		_elgg_services()->requestContext->setHttpPort(443);
		_elgg_services()->requestContext->setHost(parse_url($site_url, PHP_URL_HOST));
		_elgg_services()->requestContext->setBaseUrl(rtrim(parse_url($site_url, PHP_URL_PATH), '/'));
		
		elgg_register_route('foo:bar', [
			'path' => 'foo/bar/test',
			'handler' => function () {}
		]);
		
		$url = elgg_generate_url('foo:bar');
		
		// test if url is normalized
		$this->assertStringStartsWith(elgg_get_site_url(), $url);
		$this->assertEquals("{$site_url}foo/bar/test", $url);
	}
	
	public function urlProvider() {
		return [
			['http://localhost/'],
			['http://localhost/sub/'],
			['http://localhost/foo/'],
		];
	}
}
