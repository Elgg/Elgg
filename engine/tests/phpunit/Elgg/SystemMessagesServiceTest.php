<?php

namespace Elgg;

use Elgg\SystemMessages\RegisterSet;

class SystemMessagesServiceTest extends \Elgg\TestCase {

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

		$this->assertEquals([
			'success' => ['s1', 's2', 's3'],
			'error' => ['e1', 'e2', 'e3'],
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

	function testCanModifyRegisterSet() {
		$this->svc->addSuccessMessage(['s2', 's3']);
		$this->svc->addErrorMessage(['e1', 'e2', 'e3']);

		$set = $this->svc->loadRegisters();
		$this->assertEquals(['s2', 's3'], $set->success);
		$this->assertEquals(['e1', 'e2', 'e3'], $set->error);

		// will be filtered
		$set->success = ['', 's2'];
		$set->error = ['e1', false];
		$set->notice = ['n1', 'n2'];
		$set->invalid = true;
		$this->svc->saveRegisters($set);

		$this->assertEquals([
			'success' => ['s2'],
				], $this->svc->dumpRegister('success'));

		$this->assertEquals([
			'error' => ['e1'],
				], $this->svc->dumpRegister('error'));

		$this->assertEquals([
			'notice' => ['n1', 'n2'],
				], $this->svc->dumpRegister());
	}

	function testSystemMessagesFunction() {
		$old_svc = _elgg_services()->systemMessages;
		_elgg_services()->setValue('systemMessages', $this->svc);

		// register new messages
		$this->assertTrue(system_messages('n1', 'notice'));
		$this->assertTrue(system_messages(['s1', 's2'], 'success'));

		// missing register
		$this->assertFalse(system_messages('--', ''));

		// count
		$this->assertEquals(3, system_messages(null, '', true));

		// dump
		$this->assertEquals([
			'notice' => ['n1'],
			'success' => ['s1', 's2']
				], system_messages(null, ''));

		// clean up
		_elgg_services()->setValue('systemMessages', $old_svc);
	}

}
