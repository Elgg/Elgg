<?php

namespace Elgg\Javascript;

use Elgg\IntegrationTestCase;

class ESMServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var ESMService
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->esm;
	}
	
	public function testRuntimeModuleRegistration() {
		$this->service->register('runtime/module', 'just_testing.js');
		
		$data = $this->service->getImportMapData();
		
		$this->assertArrayHasKey('imports', $data);
		$this->assertArrayHasKey('runtime/module', $data['imports']);
		$this->assertEquals('just_testing.js', $data['imports']['runtime/module']);
	}
	
	public function testImportModules() {
		$this->assertArrayNotHasKey('my/module', $this->service->getImports());
		$this->service->import('my/module');
		$this->assertContains('my/module', $this->service->getImports());
	}
}
