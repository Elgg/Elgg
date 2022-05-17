<?php

namespace Elgg;

class SystemMessagesServiceIntegrationTest extends \Elgg\IntegrationTestCase {
	
	public function testSystemMessageRegistration() {
		elgg_register_success_message('success 1');
		elgg_register_success_message(['message' => 'success 2']);
		elgg_register_error_message('error 1');
		elgg_register_error_message(['message' => 'error 2']);
		
		$messages = _elgg_services()->system_messages->dumpRegister();
		
		$success = $messages['success'];
		$this->assertInstanceOf(\ElggSystemMessage::class, $success[0]);
		$this->assertInstanceOf(\ElggSystemMessage::class, $success[1]);
		$this->assertEquals('success 1', $success[0]->getMessage());
		$this->assertEquals('success 2', $success[1]->getMessage());

		$error = $messages['error'];
		$this->assertInstanceOf(\ElggSystemMessage::class, $error[0]);
		$this->assertInstanceOf(\ElggSystemMessage::class, $error[1]);
		$this->assertEquals('error 1', $error[0]->getMessage());
		$this->assertEquals('error 2', $error[1]->getMessage());
	}
}
