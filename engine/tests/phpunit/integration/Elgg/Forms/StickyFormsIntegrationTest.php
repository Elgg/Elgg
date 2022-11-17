<?php

namespace Elgg\Forms;

use Elgg\IntegrationTestCase;
use Elgg\Http\Request;

class StickyFormsIntegrationTest extends IntegrationTestCase {

	/**
	 * @var StickyForms
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->stickyForms;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		$this->service->clearStickyForm('foo');
		$this->service->clearStickyForm('bar');
	}
	
	/**
	 * Create the application with a prepared request
	 *
	 * @param Request $request prepared request
	 *
	 * @return void
	 */
	protected function createService(Request $request): void {
		self::createApplication([
			'isolate' => true,
			'request' => $request,
		]);
	}
	
	public function testMakeStickyFormDefaultIgnoredFields() {
		// request also container CSRF tokens, which shouldn't be made sticky
		$request = $this->prepareHttpRequest('foo', 'POST', [
			'name' => 'foo',
			'_elgg_sticky_form_name' => 'foo', // added by sticky form support
			'_elgg_sticky_ignored_fields' => 'password,password2', // added by sticky form support
			'_route' => 'action:foo', // added by router
		], 0, true);
		
		$this->createService($request);
		
		$this->service->makeStickyForm('foo');
		$this->assertEquals([
			'name' => 'foo',
		], $this->service->getStickyValues('foo'));
	}
	
	public function testMakeStickyFormWithIgnoredFields() {
		$request = $this->prepareHttpRequest('foo', 'POST', [
			'name' => 'foo',
			'ignored' => 'bar',
		]);
		
		$this->createService($request);
		
		$this->service->makeStickyForm('foo');
		$this->assertEquals([
			'name' => 'foo',
			'ignored' => 'bar',
		], $this->service->getStickyValues('foo'));
		
		$this->service->makeStickyForm('bar', ['ignored']);
		$this->assertEquals([
			'name' => 'foo',
		], $this->service->getStickyValues('bar'));
	}
	
	public function testGetSingleStickyValue() {
		$request = $this->prepareHttpRequest('foo', 'POST', [
			'name' => 'foo',
			'other_input' => 'bar',
		]);
		
		$this->createService($request);
		
		// form not yet made sticky
		$this->assertEmpty($this->service->getStickyValue('foo', 'name'));
		// make sure it can use default value
		$this->assertEquals('bar', $this->service->getStickyValue('foo', 'name', 'bar'));
		
		$this->service->makeStickyForm('foo');
		
		// get sticky value and not the default value
		$this->assertEquals('foo', $this->service->getStickyValue('foo', 'name', 'bar'));
	}
}

