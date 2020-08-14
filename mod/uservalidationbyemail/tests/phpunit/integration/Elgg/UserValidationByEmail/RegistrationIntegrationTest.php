<?php

namespace Elgg\UserValidationByEmail;

use Elgg\ActionResponseTestCase;
use Elgg\Http\OkResponse;

/**
 * Test the registration and follow up procedures
 *
 * @since 3.2
 */
class RegistrationIntegrationTest extends ActionResponseTestCase {
	
	public function up() {
		parent::up();
		
		self::createApplication(['isolate' => true]);
		elgg()->config->min_password_length = 3;
		elgg()->config->minusername = 4;
		elgg()->config->allow_registration = true;
		
		elgg_register_plugin_hook_handler('register', 'user', 'Elgg\UserValidationByEmail\User::disableUserOnRegistration');
	}
	
	public function down() {
		parent::down();
	}
	
	public function testRegistrationWithoutAdminValidation() {
		
		elgg()->config->require_admin_validation = false;
		
		// Register new user
		$username = $this->getRandomUsername();
		
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		/* @var $user \ElggUser */
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertFalse($user->isValidated());
		$this->assertFalse($user->isEnabled());
		
		$this->assertEmpty(elgg_get_logged_in_user_entity());
		
		$plugin_tracking = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
		$this->assertNotNull($plugin_tracking);
		$this->assertEmpty($plugin_tracking);
		
		// confirm email
		$link = elgg_generate_url('account:validation:email:confirm', [
			'u' => $user->guid,
		]);
		$link = elgg_http_get_signed_url($link);
		
		$request = $this->prepareHttpRequest($link);
		_elgg_services()->setValue('request', $request);
		
		$response = _elgg_services()->router->getResponse($request);
		
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		
		$this->assertTrue($user->isEnabled());
		$this->assertTrue($user->isValidated());
		
		$this->assertEquals($user->guid, elgg_get_logged_in_user_guid());
		
		$plugin_tracking = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
		$this->assertNotEmpty($plugin_tracking);
		
		elgg_get_session()->removeLoggedInUser();
	}
	
	public function testRegistrationWithAdminValidation() {
		
		elgg()->config->require_admin_validation = true;
		
		// Register new user
		$username = $this->getRandomUsername();
		
		$response = $this->executeAction('register', [
			'username' => $username,
			'password' => '1111111111111',
			'password2' => '1111111111111',
			'email' => $this->getRandomEmail(),
			'name' => 'Test User',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		/* @var $user \ElggUser */
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertFalse($user->isValidated());
		$this->assertFalse($user->isEnabled());
		
		$this->assertEmpty(elgg_get_logged_in_user_entity());
		
		$plugin_tracking = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
		$this->assertNotNull($plugin_tracking);
		$this->assertEmpty($plugin_tracking);
		
		// confirm email
		$link = elgg_generate_url('account:validation:email:confirm', [
			'u' => $user->guid,
		]);
		$link = elgg_http_get_signed_url($link);
		
		$request = $this->prepareHttpRequest($link);
		_elgg_services()->setValue('request', $request);
		
		$response = _elgg_services()->router->getResponse($request);
		
		$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return get_user_by_username($username);
		});
		
		$this->assertFalse($user->isEnabled());
		$this->assertFalse($user->isValidated());
		
		$this->assertEmpty(elgg_get_logged_in_user_guid());
		
		$plugin_tracking = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
		$this->assertNotEmpty($plugin_tracking);
	}
}
