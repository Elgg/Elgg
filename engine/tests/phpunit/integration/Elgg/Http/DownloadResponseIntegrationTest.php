<?php

namespace Elgg\Http;

use Elgg\IntegrationTestCase;

class DownloadResponseIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @dataProvider getDownloadResponseProvider
	 */
	public function testGetDownloadResponse(string $content, string $filename = '', bool $inline = false, array $headers = []) {
		$response = elgg_download_response($content, $filename, $inline, $headers);
		
		$this->assertInstanceOf(DownloadResponse::class, $response);
		$this->assertEquals($content, $response->getContent());
		
		$response_headers = $response->getHeaders();
		$this->assertIsArray($response_headers);
		$this->assertArrayHasKey('Content-Disposition', $response_headers);
		
		if (!isset($headers['Content-Disposition'])) {
			if (!empty($filename)) {
				$this->assertStringContainsString("filename=\"{$filename}\"", $response_headers['Content-Disposition']);
			} else {
				$this->assertStringNotContainsString('filename', $response_headers['Content-Disposition']);
			}
			
			if ($inline) {
				$this->assertStringContainsString('inline', $response_headers['Content-Disposition']);
			} else {
				$this->assertStringContainsString('attachment', $response_headers['Content-Disposition']);
			}
		}
		
		foreach ($headers as $key => $value) {
			$this->assertArrayHasKey($key, $response_headers);
			$this->assertEquals($value, $response_headers[$key]);
		}
	}
	
	public static function getDownloadResponseProvider() {
		return [
			['foo'],
			['foo', 'bar.txt'],
			['foo', 'bar.txt', true],
			['foo', 'bar.txt', false, ['Content-Type' => 'text/plain; charset=utf-8']],
			['foo', 'bar.txt', false, [
				'Content-Type' => 'text/json; charset=utf-8',
				'Content-Disposition' => 'inline; filename="bar.json"',
			]],
		];
	}
}
