<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\Request;
use Elgg\IntegrationTestCase;

class DownloadActionIntegrationTest extends IntegrationTestCase {
		
	protected function executeRequest(Request $request) {
		$request->_integration_testing = true;
		
		ob_start();
		
		$t = false;
		$response = false;
		try {
			_elgg_services()->router->route($request);
			$response = _elgg_services()->responseFactory->getSentResponse();
		} catch (\Throwable $t) {
			// just catching
		}
		
		ob_get_clean();
		
		if ($t instanceof \Throwable) {
			throw $t;
		}
		
		return $response;
	}
		
	public function testJsonDownloadAction() {
		$request = $this->prepareHttpRequest(elgg_generate_action_url('testing/download/json'));
		
		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		$app->internal_services->routes->register('action:testing/download/json', [
			'path' => 'action/testing/download/json',
			'controller' => \Elgg\Helpers\Actions\JsonDownloadAction::class,
			'walled' => false,
		]);
		
		$response = $this->executeRequest($request);
		
		$this->assertTrue($response->isOk());
		$this->assertEquals(json_encode(['key' => 'value']), $response->getContent());
	}	
	
	public function testCsvDownloadAction() {
		$request = $this->prepareHttpRequest(elgg_generate_action_url('testing/download/csv'));

		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);

		$app->internal_services->routes->register('action:testing/download/csv', [
			'path' => 'action/testing/download/csv',
			'controller' => \Elgg\Helpers\Actions\CsvDownloadAction::class,
			'walled' => false,
		]);

		$response = $this->executeRequest($request);

		$this->assertTrue($response->isOk());

		$fh_temp = new \ElggTempFile();
		$fh = $fh_temp->open('write');

		fputcsv($fh, ['first', 'second'], ';', '"', '\\');
		fputcsv($fh, ['row1col1', 'row1col2'], ';', '"', '\\');
		fputcsv($fh, ['row2col1', 'row2col2'], ';', '"', '\\');

		$expected_results = $fh_temp->grabFile();
		$fh_temp->close();		
		
		$this->assertEquals($expected_results, $response->getContent());
	}
}
