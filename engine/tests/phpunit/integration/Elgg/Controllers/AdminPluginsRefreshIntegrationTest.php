<?php

namespace Elgg\Controllers;

use Elgg\IntegrationTestCase;
use Elgg\Exceptions\Http\Gatekeeper\AdminGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\AjaxGatekeeperException;
use Elgg\Http\Request;

class AdminPluginsRefreshIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		elgg_unregister_route('admin_plugins_refresh');
		
		elgg()->session->removeLoggedInUser();
	}
	
	protected function createService(Request $request) {
		$app = self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		// keep this inline with the route declaration in /engine/routes.php
		$app->_services->routes->register('admin_plugins_refresh', [
			'path' => '/admin_plugins_refresh',
			'controller' => \Elgg\Controllers\AdminPluginsRefresh::class,
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
				\Elgg\Router\Middleware\AjaxGatekeeper::class,
			],
		]);
	}
	
	protected function executeRequest(Request $request) {
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
	
	public function testNonAdminRequest() {
		$request = $this->prepareHttpRequest('/admin_plugins_refresh', 'GET', [], 1);
		$this->createService($request);
		
		$user = $this->createUser();
		elgg()->session->setLoggedInUser($user);
		
		$this->expectException(AdminGatekeeperException::class);
		$this->executeRequest($request);
		
		$user->delete();
	}
	
	public function testNonAjaxRequest() {
		$request = $this->prepareHttpRequest('/admin_plugins_refresh');
		$this->createService($request);
		
		$admin = $this->getAdmin();
		elgg()->session->setLoggedInUser($admin);
		
		$this->expectException(AjaxGatekeeperException::class);
		$this->executeRequest($request);
		
		$admin->delete();
	}
	
	public function testAdminRequest() {
		$request = $this->prepareHttpRequest('/admin_plugins_refresh', 'GET', [], 1);
		$this->createService($request);
		
		$admin = $this->getAdmin();
		elgg()->session->setLoggedInUser($admin);
		
		$response = $this->executeRequest($request);
		
		$this->assertTrue($response->isOk());
		$this->assertNotEmpty($response->getContent());
		
		$admin->delete();
	}
}
