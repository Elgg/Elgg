<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class SystemMessagesServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var SystemMessagesService
	 */
	protected $svc;

	/**
	 * @var \ElggSession
	 */
	protected $session;

	public function up() {
		$this->session = _elgg_services()->session;
		$this->svc = new SystemMessagesService($this->session);
	}

	function testCanStoreAndDumpMessages() {
		$this->svc->addSuccessMessage('s1');
		$this->svc->addSuccessMessage('s2');
		$this->svc->addSuccessMessage('s3');

		$this->svc->addErrorMessage('e1');
		$this->svc->addErrorMessage('e2');
		$this->svc->addErrorMessage('e3');

		$this->assertEquals([
			'success' => ['s1', 's2', 's3'],
			'error' => ['e1', 'e2', 'e3'],
		], $this->svc->dumpRegister());

		$this->assertEmpty($this->svc->dumpRegister());
	}

	function testMessagesStoredInSession() {
		$this->svc->addSuccessMessage('s1');

		$this->assertEquals(['success' => ['s1']], $this->session->get(SystemMessagesService::SESSION_KEY));
	}

	function testCanDumpOneRegister() {
		$this->svc->addSuccessMessage('s2');
		$this->svc->addSuccessMessage('s3');
		$this->svc->addErrorMessage('e1');
		$this->svc->addErrorMessage('e2');
		$this->svc->addErrorMessage('e3');

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
		$this->svc->addSuccessMessage('s2');
		$this->svc->addSuccessMessage('s3');
		$this->svc->addErrorMessage('e1');
		$this->svc->addErrorMessage('e2');
		$this->svc->addErrorMessage('e3');

		$this->assertEquals(2, $this->svc->count("success"));
		$this->assertEquals(3, $this->svc->count("error"));
		$this->assertEquals(5, $this->svc->count());
	}

	function testCanModifyRegister() {
		$s2 = new \ElggSystemMessage('s2', 'success');
		$s3 = new \ElggSystemMessage('s3', 'success');

		$e1 = new \ElggSystemMessage('e1', 'error');
		$e2 = new \ElggSystemMessage('e2', 'error');
		$e3 = new \ElggSystemMessage('e3', 'error');
		
		$this->svc->addMessage($s2);
		$this->svc->addMessage($s3);
		$this->svc->addMessage($e1);
		$this->svc->addMessage($e2);
		$this->svc->addMessage($e3);

		$set = $this->svc->loadRegisters();
		$this->assertEquals([$s2, $s3], $set['success']);
		$this->assertEquals([$e1, $e2, $e3], $set['error']);

		// will be filtered
		$set['success'] = ['', $s2];
		$set['error'] = [$e1, false];
		$set['invalid'] = true;
		$this->svc->saveRegisters($set);

		$this->assertEquals([
			'success' => [$s2],
		], $this->svc->dumpRegister('success'));

		$this->assertEquals([
			'error' => [$e1],
		], $this->svc->dumpRegister('error'));
	}
}
