<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test helper functions
 *
 * @group IntegrationTests
 * @group Helpers
 */
class ElggCoreHelpersTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		_elgg_services()->externalFiles->reset();
	}

	/**
	 * Test elgg_normalize_url()
	 */
	public function testElggNormalizeURL() {
		$conversions = [
			'http://example.com' => 'http://example.com',
			'https://example.com' => 'https://example.com',
			'http://example-time.com' => 'http://example-time.com',

			'http://in:valid~url' => elgg_get_site_url() . 'http://in:valid~url',
			'https://in:valid~url' => elgg_get_site_url() . 'https://in:valid~url',

			'//example.com' => '//example.com',
			'ftp://example.com/file' => 'ftp://example.com/file',
			'mailto:brett@elgg.org' => 'mailto:brett@elgg.org',
			'javascript:alert("test")' => 'javascript:alert("test")',
			'app://endpoint' => 'app://endpoint',
			'tel:+1111111111' => 'tel:+1111111111',

			'example.com' => 'http://example.com',
			'example.com/subpage' => 'http://example.com/subpage',

			'http://example.com/ИмяПользователя' => 'http://example.com/ИмяПользователя',

			'http://example.com/a b' => 'http://example.com/a%20b',
			'http://example.com/?a=1 2' => 'http://example.com/?a=1%202',

			'page/handler' => elgg_get_site_url() . 'page/handler',
			'page/handler?p=v&p2=v2' => elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'mod/plugin/file.php' => elgg_get_site_url() . 'mod/plugin/file.php',
			'mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'rootfile.php' => elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' => elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',

			'/page/handler' => elgg_get_site_url() . 'page/handler',
			'/page/handler?p=v&p2=v2' => elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' => elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' => elgg_get_site_url() . 'rootfile.php',
			'/robots.txt' => elgg_get_site_url() . 'robots.txt',
			'/rootfile.php?p=v&p2=v2' => elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',
		];

		foreach ($conversions as $input => $output) {
			$this->assertEquals($output, elgg_normalize_url($input));
		}
	}

	/**
	 * Test elgg_get_loaded_external_resources('js')
	 */
	public function testElggGetJS() {
		$base = trim(elgg_get_site_url(), "/");

		$urls = [
			'id1' => "$base/urla",
			'id2' => "$base/urlb",
			'id3' => "$base/urlc",
		];

		foreach ($urls as $id => $url) {
			elgg_register_external_file('js', $id, $url, 'head');
			elgg_load_external_file('js', $id);
		}

		$js_urls = elgg_get_loaded_external_resources('js', 'head');
		$this->assertIsArray($js_urls);

		$this->assertEquals($urls['id1'], $js_urls['id1']->url);
		$this->assertEquals($urls['id2'], $js_urls['id2']->url);
		$this->assertEquals($urls['id3'], $js_urls['id3']->url);

		$js_urls = elgg_get_loaded_external_resources('js', 'footer');
		$this->assertEquals([], $js_urls);
	}

	/**
	 * Test elgg_get_loaded_external_resources('js')
	 */
	public function testRegisterExternalDefaultLocations() {
		$base = trim(elgg_get_site_url(), '/');

		elgg_register_external_file('js', 'id1', "$base/urla");
		elgg_load_external_file('js', 'id1');

		elgg_register_external_file('css', 'id2', "$base/urlb");
		elgg_load_external_file('css', 'id2');
		
		$this->assertArrayHasKey('id1', elgg_get_loaded_external_resources('js', 'footer'));
		$this->assertArrayHasKey('id2', elgg_get_loaded_external_resources('css', 'head'));
		
	}
}
