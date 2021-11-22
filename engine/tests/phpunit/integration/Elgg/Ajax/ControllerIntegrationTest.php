<?php

namespace Elgg\Ajax;

use Elgg\IntegrationTestCase;

class ControllerIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		$this->registerViews();
	}

	public function down() {
		elgg_get_session()->removeLoggedInUser();
		
		if (isset($this->user)) {
			elgg_call(ELGG_IGNORE_ACCESS, function() {
				$this->user->delete();
			});
		}
	}
	
	protected function prepareService(\Elgg\Http\Request $request) {
		$this->createApplication([
			'isolate' => true,
			'request' => $request,
		]);
		
		$this->registerViews();
	}
	
	protected function executeRequest(\Elgg\Http\Request $request, bool $prepare = true) {
		if ($prepare) {
			$this->prepareService($request);
		}
		
		return _elgg_services()->router->getResponse($request);
	}
	
	protected function registerViews() {
		$views_dir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "{$views_dir}/default", 'default');
		
		elgg_register_ajax_view('ajax_test/registered');
		elgg_register_ajax_view('admin/ajax_test/registered');
		elgg_register_ajax_view('forms/ajax_test/registered');
		elgg_register_ajax_view('forms/admin/ajax_test/registered');
	}
	
	public function testCanLoadRegisteredAjaxView() {
		$request = $this->prepareHttpRequest('ajax/view/ajax_test/registered', 'GET', [], 1);
		
		$response = $this->executeRequest($request);
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals('registered', $response->getContent());
	}
	
	public function testCantLoadRegisteredAjaxViewWithNonAjaxRequest() {
		$request = $this->prepareHttpRequest('ajax/view/ajax_test/registered');
		
		$this->expectException(\Elgg\Http\Exception\AjaxGatekeeperException::class);
		$this->executeRequest($request);
	}
	
	public function testCantLoadNonRegisteredAjaxView() {
		$this->assertTrue(elgg_view_exists('ajax_test/not_registered'));
		$request = $this->prepareHttpRequest('ajax/view/ajax_test/not_registered', 'GET', [], 1);
		
		$response = $this->executeRequest($request);
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
	}
	
	public function testCantAccessAdminAjaxViewLoggedOut() {
		$request = $this->prepareHttpRequest('ajax/view/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->expectException(\Elgg\Http\Exception\LoggedInGatekeeperException::class);
		$this->executeRequest($request);
	}
	
	public function testCantAccessAdminAjaxViewAsNonAdmin() {
		$request = $this->prepareHttpRequest('ajax/view/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->prepareService($request);
		
		$this->user = $user = $this->createUser();
		elgg_get_session()->setLoggedInUser($user);
		
		$this->expectException(\Elgg\Http\Exception\AdminGatekeeperException::class);
		$this->executeRequest($request, false);
	}
	
	public function testCanAccessAdminAjaxViewAsAdmin() {
		$request = $this->prepareHttpRequest('ajax/view/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->prepareService($request);
		
		$user = $this->getAdmin();
		elgg_get_session()->setLoggedInUser($user);
		
		$response = $this->executeRequest($request, false);
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals('registered', $response->getContent());
	}
	
	public function testCanLoadRegisteredAjaxForm() {
		$request = $this->prepareHttpRequest('ajax/form/ajax_test/registered', 'GET', [], 1);
		
		$response = $this->executeRequest($request);
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertStringContainsString('registered', $response->getContent());
	}
	
	public function testCantLoadRegisteredAjaxFormWithNonAjaxRequest() {
		$request = $this->prepareHttpRequest('ajax/form/ajax_test/registered');
		
		$this->expectException(\Elgg\Http\Exception\AjaxGatekeeperException::class);
		$this->executeRequest($request);
	}
	
	public function testCantLoadNonRegisteredAjaxForm() {
		$this->assertTrue(elgg_view_exists('ajax_test/not_registered'));
		$request = $this->prepareHttpRequest('ajax/form/ajax_test/not_registered', 'GET', [], 1);
		
		$response = $this->executeRequest($request);
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
	}
	
	public function testCantAccessAdminAjaxFormLoggedOut() {
		$request = $this->prepareHttpRequest('ajax/form/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->expectException(\Elgg\Http\Exception\LoggedInGatekeeperException::class);
		$this->executeRequest($request);
	}
	
	public function testCantAccessAdminAjaxFormAsNonAdmin() {
		$request = $this->prepareHttpRequest('ajax/form/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->prepareService($request);
		
		$this->user = $user = $this->createUser();
		elgg_get_session()->setLoggedInUser($user);
		
		$this->expectException(\Elgg\Http\Exception\AdminGatekeeperException::class);
		$this->executeRequest($request, false);
	}
	
	public function testCanAccessAdminAjaxFormAsAdmin() {
		$request = $this->prepareHttpRequest('ajax/form/admin/ajax_test/registered', 'GET', [], 1);
		
		$this->prepareService($request);
		
		$user = $this->getAdmin();
		elgg_get_session()->setLoggedInUser($user);
		
		$response = $this->executeRequest($request, false);
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertStringContainsString('registered', $response->getContent());
	}
}
