<?php

namespace Elgg;

use Elgg\Database\Update;
use Elgg\Database\UsersRememberMeCookiesTable;

class PersistentLoginServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggCookie
	 */
	protected $cookie;
	
	/**
	 * @var PersistentLoginService
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->persistentLogin;
	}
	
	public function testMakeLoginPersistent() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$this->assertEquals($this->cookie->value, _elgg_services()->session->get('code'));
	}
	
	public function testRemovePersistentLogin() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$service = $this->mockServiceWithToken($this->cookie->value);
		$this->cookie = null;
		
		$service->removePersistentLogin();
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertLessThan(time(), $this->cookie->expire);
		
		$this->assertEmpty(_elgg_services()->session->get('code'));
	}
	
	public function testHandlePasswordChange() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token);
		$this->cookie = null;
		
		$service->handlePasswordChange($user);
		$this->assertEmpty($this->cookie);
		
		$this->assertEmpty($service->getUserFromToken($cookie_token));
	}
	
	public function testHandlePasswordChangeWithSameModifier() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token);
		$this->cookie = null;
		
		$service->handlePasswordChange($user, $user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		$this->assertNotEquals($cookie_token, $this->cookie->value);
		
		$found = $service->getUserFromToken($this->cookie->value);
		$this->assertInstanceOf(\ElggUser::class, $found);
		$this->assertEquals($user->guid, $found->guid);
	}
	
	public function testHandlePasswordChangeWithDifferentModifier() {
		$user = $this->createUser();
		$other_user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token);
		$this->cookie = null;
		
		$service->handlePasswordChange($user, $other_user);
		$this->assertEmpty($this->cookie);
		
		$this->assertEmpty($service->getUserFromToken($cookie_token));
	}
	
	public function testBootSessionWithoutToken() {
		$service = $this->mockService();
		
		$this->assertNull($service->bootSession());
	}
	
	public function testBootSessionWithValidToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token);
		$this->cookie = null;
		
		$found = $service->bootSession();
		$this->assertInstanceOf(\ElggUser::class, $found);
		$this->assertEquals($user->guid, $found->guid);
	}
	
	public function testBootSessionWithInvalidToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token . 'invalid');
		$this->cookie = null;
		
		$this->assertNull($service->bootSession());
	}
	
	public function testGetUserFromEmptyToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$this->assertNull($service->getUserFromToken(''));
	}
	
	public function testGetUserFromToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$found = $service->getUserFromToken($cookie_token);
		$this->assertInstanceOf(\ElggUser::class, $found);
		$this->assertEquals($user->guid, $found->guid);
	}
	
	public function testGetUserFromInvalidToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$this->assertNull($service->getUserFromToken($cookie_token . 'invalid'));
	}
	
	public function testGetUserFromEmptyHash() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$this->assertNull($service->getUserFromHash(''));
	}
	
	public function testGetUserFromHash() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$found = $service->getUserFromHash(md5($cookie_token));
		$this->assertInstanceOf(\ElggUser::class, $found);
		$this->assertEquals($user->guid, $found->guid);
	}
	
	public function testGetUserFromInvalidHash() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$this->assertNull($service->getUserFromHash($cookie_token));
	}
	
	public function testUpdateTokenUsageWithoutServiceToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$this->assertNull($service->updateTokenUsage($user));
	}
	
	public function testUpdateTokenUsageWithServiceToken() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$cookie_token = $this->cookie->value;
		
		$service = $this->mockServiceWithToken($cookie_token);
		$this->cookie = null;
		
		$this->assertTrue($service->updateTokenUsage($user));
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$this->assertEquals($cookie_token, $this->cookie->value);
	}
	
	public function testRemoveExiredTokensWithInvalidTime() {
		$service = $this->mockService();
		
		$this->assertFalse($service->removeExpiredTokens('+10 years'));
	}
	
	public function testRemoveExpiredTokens() {
		$user = $this->createUser();
		$service = $this->mockService();
		
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$expired_cookie_token = $this->cookie->value;
		unset($this->cookie);
		
		// update the timestamp of the tokens to expire
		$update = Update::table(UsersRememberMeCookiesTable::TABLE_NAME);
		$update->set('timestamp', $update->param(1, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $user->guid, ELGG_VALUE_STRING));
		
		$this->assertTrue(_elgg_services()->db->updateData($update));
		
		// add another token
		$service->makeLoginPersistent($user);
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertIsString($this->cookie->value);
		
		$valid_cookie_token = $this->cookie->value;
		
		$this->assertTrue($service->removeExpiredTokens(time()));
		
		$this->assertNull($service->getUserFromToken($expired_cookie_token));
		
		$found = $service->getUserFromToken($valid_cookie_token);
		$this->assertInstanceOf(\ElggUser::class, $found);
		$this->assertEquals($user->guid, $found->guid);
	}
	
	/**
	 * Helper functions
	 */
	
	protected function mockService(): PersistentLoginService {
		$service = clone $this->service;
		$service->_callable_elgg_set_cookie = [$this, 'mockSetCookie'];
		
		return $service;
	}
	
	protected function mockServiceWithToken(string $token): PersistentLoginService {
		$request = _elgg_services()->request;
		$config = _elgg_services()->config;
		
		$global_cookies_config = $config->getCookieConfig();
		$cookie_name = $global_cookies_config['remember_me']['name'];
		$request->cookies->set($cookie_name, $token);
		
		$service = new PersistentLoginService(
			_elgg_services()->users_remember_me_cookies_table,
			_elgg_services()->session,
			_elgg_services()->crypto,
			$config,
			$request);
		
		$service->_callable_elgg_set_cookie = [$this, 'mockSetCookie'];
		
		return $service;
	}
	
	public function mockSetCookie(\ElggCookie $cookie): void {
		$this->cookie = $cookie;
	}
}
