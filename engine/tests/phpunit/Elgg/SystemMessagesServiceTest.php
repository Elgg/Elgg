<?php
namespace Elgg;

class SystemMessagesServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var SystemMessagesService
	 */
	protected $svc;

	/**
	 * @var \ElggSession
	 */
	protected $session;

	function setup() {
		$this->session = \ElggSession::getMock();
		$this->svc = new SystemMessagesService($this->session);
	}

	function testCanStoreAndDumpMessages() {
		$this->svc->addSuccessMessage('s1');
		$this->svc->addSuccessMessage(['s2', 's3']);

		$this->svc->addErrorMessage('e1');
		$this->svc->addErrorMessage(['e2', 'e3']);

		$this->svc->addMessageToRegister('n1', 'notice');
		$this->svc->addMessageToRegister(['n2', 'n3'], 'notice');

		$this->assertEquals([
			'success' => ['s1', 's2', 's3'],
			'error' => ['e1', 'e2', 'e3'],
			'notice' => ['n1', 'n2', 'n3'],
		], $this->svc->dumpRegister());

		$this->assertEmpty($this->svc->dumpRegister());
	}

	function testMessagesStoredInSession() {
		$this->svc->addSuccessMessage('s1');

		$this->assertEquals(['success' => ['s1']], $this->session->get('msg'));
	}

	function testCanDumpOneRegister() {
		$this->svc->addSuccessMessage(['s2', 's3']);
		$this->svc->addErrorMessage(['e1', 'e2', 'e3']);

		$this->assertEquals([
			'success' => ['s2', 's3'],
		], $this->svc->dumpRegister('success'));

		$this->assertEquals([
			'success' => [],
		], $this->svc->dumpRegister('success'));

		$this->assertEquals([
			'error' => ['e1', 'e2', 'e3'],
		], $this->svc->dumpRegister('error'));

		$this->assertEmpty($this->svc->dumpRegister());
	}

	function testCanCount() {
		$this->svc->addSuccessMessage(['s2', 's3']);
		$this->svc->addErrorMessage(['e1', 'e2', 'e3']);

		$this->assertEquals(2, $this->svc->count("success"));
		$this->assertEquals(3, $this->svc->count("error"));
		$this->assertEquals(5, $this->svc->count());
	}
}
