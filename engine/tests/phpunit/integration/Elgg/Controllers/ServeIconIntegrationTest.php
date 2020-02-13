<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\Request;
use Elgg\IntegrationTestCase;

class ServeIconIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	protected function createService(Request $request) {
		$request->_integration_testing = true;
		
		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		// keep this inline with the route declaration in /engine/routes.php
		$app->_services->routes->register('serve-icon', [
			'path' => '/serve-icon/{guid}/{size}',
			'controller' => \Elgg\Controllers\ServeIcon::class,
			'walled' => false,
		]);
	}
	
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
	
	public function testUnKnownEntity() {
		$request = $this->prepareHttpRequest('/serve-icon/1234567890/small');
		
		$this->createService($request);
		
		$this->expectException(EntityNotFoundException::class);
		$this->executeRequest($request);
	}
	
	public function testUnKnownIcon() {
		$entity = $this->createObject();
		
		$request = $this->prepareHttpRequest("/serve-icon/{$entity->guid}/small");
		
		$this->createService($request);
		
		$this->expectException(EntityNotFoundException::class);
		$this->expectExceptionMessage('Icon does not exist');
		$this->executeRequest($request);
		
		$entity->delete();
	}
	
	public function testServeIcon() {
		$entity = $this->createObject();
		$entity->saveIconFromLocalFile($this->normalizeTestFilePath('dataroot/1/1/300x300.jpg'));
		
		$request = $this->prepareHttpRequest("/serve-icon/{$entity->guid}/small");
		
		$this->createService($request);
		$response = $this->executeRequest($request);
		
		$this->assertTrue($response->isOk());
		
		$entity->delete();
	}
}

