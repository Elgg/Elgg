<?php

namespace Elgg\Actions\User;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class ValidateIntegrationTest extends ActionResponseTestCase {
	
	public function up() {
		
		$session = elgg_get_session();
		$session->setLoggedInUser($this->getAdmin());
	}
	
	public function down() {
		
		$session = elgg_get_session();
		$session->removeLoggedInUser();
	}
	
	public function testValidateSingleUserWithoutParams() {
		
		$response = $this->executeAction('admin/user/validate');
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testValidateSingleUserWithInvalidParams() {
		
		$object = $this->createObject();
		$response = $this->executeAction('admin/user/validate', [
			'user_guid' => $object->guid,
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testValidateSingleUserWithValidatedUser() {
		
		$user = $this->createUser();
		$user->setValidationStatus(true);
		
		$response = $this->executeAction('admin/user/validate', [
			'user_guid' => $user->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
	}
	
	public function testValidateSingleUserWithUnvalidatedUser() {
		
		$user = $this->createUser();
		$user->setValidationStatus(false);
		
		$response = $this->executeAction('admin/user/validate', [
			'user_guid' => $user->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertTrue($user->isValidated());
	}
	
	public function testValidateBulkUsersWithoutParams() {
		
		$response = $this->executeAction('admin/user/bulk/validate');
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testValidateBulkUsersWithUsers() {
		
		$valid_user = $this->createUser();
		$valid_user->setValidationStatus(true);
		
		$invalid_user = $this->createUser();
		$invalid_user->setValidationStatus(false);
		
		$response = $this->executeAction('admin/user/bulk/validate', [
			'user_guids' => [
				$valid_user->guid,
				$invalid_user->guid,
			],
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		$this->assertTrue($invalid_user->isValidated());
	}
}
