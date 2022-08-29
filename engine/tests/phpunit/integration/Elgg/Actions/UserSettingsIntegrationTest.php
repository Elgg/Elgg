<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\OkResponse;

/**
 * @group ActionsService
 * @group AccountActions
 * @group Settings
 * @group UserSettings
 */
class UserSettingsIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		_elgg_services()->config->min_password_length = 3;
		_elgg_services()->config->minusername = 4;
		_elgg_services()->config->allow_registration = true;

		_elgg_services()->hooks->backup();
		_elgg_services()->events->backup();

		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setLanguage');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setPassword');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setDefaultAccess');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setName');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setUsername');
		elgg_register_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setEmail');
		
		elgg_register_plugin_hook_handler('registeruser:validate:password', 'all', [_elgg_services()->passwordGenerator, 'registerUserPasswordValidation']);
	}

	public function down() {
		_elgg_services()->hooks->restore();
		_elgg_services()->events->restore();

		parent::down();
	}

	public function testPasswordChangeFailsWithoutValidCurrentPassword() {
		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'password' => '124567890',
			'password2' => '12567890',
			'current_password' => 'wrong',
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('LoginException:ChangePasswordFailure'));
	}

	public function testPasswordChangeFailsWithInvalidNewPassword() {
		$pwd = elgg_generate_password();

		$user = $this->createUser();
		$user->setPassword($pwd);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'password' => '12',
			'password2' => '12',
			'current_password' => $pwd,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('Security:InvalidPasswordLengthException', [elgg()->config->min_password_length]));
	}

	public function testPasswordChangeFailsWithMismatchingNewPassword() {
		$pwd = elgg_generate_password();

		$user = $this->createUser();
		$user->setPassword($pwd);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'password' => '12345678',
			'password2' => '1234567 8',
			'current_password' => $pwd,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('RegistrationException:PasswordMismatch'));
	}

	public function testPasswordChangeSucceeds() {
		$pwd = elgg_generate_password();

		$user = $this->createUser();
		$user->setPassword($pwd);

		$old_hash = $user->password_hash;

		_elgg_services()->session->setLoggedInUser($user);

		$new_pwd = elgg_generate_password();

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'password' => $new_pwd,
			'password2' => $new_pwd,
			'current_password' => $pwd,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('user:password:success'));

		$this->assertNotEquals($user->password_hash, $old_hash);
	}

	public function testDisplayNameChangeFails() {
		$user = $this->createUser();

		$name = $user->name;

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'name' => '<a></a>',
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('user:name:fail'));

		$this->assertEquals($user->name, $name);
	}

	public function testDisplayNameChangeSucceeds() {
		$user = $this->createUser();

		$new_name = $this->faker->name;

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'name' => $new_name,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('user:name:success'));

		$this->assertEquals($new_name, $user->name);
	}

	public function testUsernameChangeFails() {

		$other = $this->createUser();
		$user = $this->createUser();
		$admin = $this->createUser([], [
			'admin' => 'yes',
		]);

		$username = $user->username;

		_elgg_services()->session->setLoggedInUser($admin);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'username' => $other->username,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('registration:userexists'));

		$this->assertEquals($user->username, $username);
	}

	public function testUsernameChangeSucceeds() {

		$user = $this->createUser();
		$admin = $this->createUser([], [
			'admin' => 'yes',
		]);

		$new_username = $this->getRandomUsername();

		_elgg_services()->session->setLoggedInUser($admin);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'username' => $new_username,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('user:username:success'));

		$this->assertEquals($new_username, $user->username);
	}

	public function testLanguageChangeSucceeds() {

		$user = $this->createUser([], ['language' => 'en']);

		// Go through the allowed languages and find the first non-English language to change the user to
		// this is done in case the database has a limited number of allowed languages
		$new_language = false;
		$allowed_languages = _elgg_services()->translator->getAllowedLanguages();
		foreach ($allowed_languages as $language) {
			if ($language === 'en') {
				continue;
			}
			$new_language = $language;
			break;
		}
		
		if (empty($new_language)) {
			$this->markTestSkipped();
		}

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'language' => $new_language,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('user:language:success'));

		$this->assertEquals($new_language, $user->getLanguage());
	}

	public function testEmailChangeFailsWithExistingEmail() {

		$other = $this->createUser();
		$user = $this->createUser();

		$email = $user->email;

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'email' => $other->email,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('registration:dupeemail'));

		$this->assertEquals($email, $user->email);
	}

	public function testEmailChangeFailsWithWrongPassword() {

		elgg()->config->security_email_require_password = true;

		$user = $this->createUser();

		$email = $user->email;

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'email' => $this->getRandomEmail(),
			'email_password' => '123',
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertErrorMessageEmitted(elgg_echo('email:save:fail:password'));

		$this->assertEquals($email, $user->email);
	}

	public function testEmailChangeSucceeds() {

		elgg()->config->security_email_require_password = true;
		elgg()->config->security_email_require_confirmation = false;

		$pwd = elgg_generate_password();

		$user = $this->createUser();
		$user->setPassword($pwd);

		$new_email = $this->getRandomEmail();

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'email' => $new_email,
			'email_password' => $pwd,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertEquals($new_email, $user->email);

		$this->assertSystemMessageEmitted(elgg_echo('email:save:success'));
	}

	public function testDefaultAccessChangeSucceeds() {

		elgg()->config->allow_user_default_access = true;

		$user = $this->createUser();
		$user->setMetadata('elgg_default_access', ACCESS_PUBLIC);

		_elgg_services()->session->setLoggedInUser($user);

		$response = $this->executeAction('usersettings/save', [
			'guid' => $user->guid,
			'default_access' => ACCESS_PRIVATE,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertSystemMessageEmitted(elgg_echo('user:default_access:success'));

		$this->assertEquals(ACCESS_PRIVATE, elgg_get_default_access($user));
	}
}
