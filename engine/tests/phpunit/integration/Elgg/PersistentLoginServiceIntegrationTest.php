<?php

namespace Elgg;

use Elgg\Database\Update;

class PersistentLoginServiceIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var PersistentLoginService
	 */
	protected $service;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * @var \ElggCookie
	 */
	protected $cookie;
	
	public function up() {
		$this->service = _elgg_services()->persistentLogin;
		$this->service->_callable_elgg_set_cookie = [$this, 'setCookie'];
		
		$this->user = $this->createUser();
	}
	
	public function down() {
		
		$this->user->delete();
		
		unset($this->cookie);
	}
	
	
	public function testMakePersistentLogin() {
		$user = $this->user;
		$service = $this->service;
		
		$this->assertEmpty($this->cookie);
		
		$service->makeLoginPersistent($user);
		
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		
		$token = $this->cookie->value;
		$this->assertNotEmpty($token);
		
		$hash = $this->hashToken($token);
		$persistent_user = $service->getUserFromHash($hash);
		
		$this->assertEquals($user, $persistent_user);
	}
	
	public function testRemovePersistentLogin() {
		$user = $this->user;
		$service = $this->service;
		
		$this->assertEmpty($this->cookie);
		
		$token = $this->makePersistentLoginToken($user);
		
		$new_service = $this->getServiceBasedOnCookie($token);
		
		$new_service->removePersistentLogin();
		
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		$this->assertEmpty($this->cookie->value);
		$this->assertLessThan(time(), $this->cookie->expire);
		
		$hash = $this->hashToken($token);
		$this->assertNull($service->getUserFromHash($hash));
	}
	
	public function testHandlePasswordChange() {
		$user = $this->user;
		$service = $this->service;
		
		$this->assertEmpty($this->cookie);
		
		$token = $this->makePersistentLoginToken($user);
		
		$service->handlePasswordChange($user);
		
		$hash = $this->hashToken($token);
		$this->assertNull($service->getUserFromHash($hash));
	}
	
	public function testUpdateTokenUsage() {
		$user = $this->user;
		$service = $this->service;
		
		$this->assertEmpty($this->cookie);
		
		$token = $this->makePersistentLoginToken($user);
		
		$new_service = $this->getServiceBasedOnCookie($token);
		
		$this->assertTrue($new_service->updateTokenUsage($user));
	}
	
	public function testRemoveAllHashes() {
		
		$user = $this->user;
		$service = $this->service;
		
		// generate mulitple tokens to test
		$tokens = [];
		for ($i = 0; $i <= 5; $i++) {
			$tokens[] = $this->makePersistentLoginToken($user);
		}
		
		$different_user = $this->createUser();
		
		$remaining_token = $this->makePersistentLoginToken($different_user);
		
		$service->removeAllHashes($user);
		
		foreach ($tokens as $token) {
			$hash = $this->hashToken($token);
			$this->assertNull($service->getUserFromHash($hash));
		}
		
		$remaining_hash = $this->hashToken($remaining_token);
		$this->assertEquals($different_user, $service->getUserFromHash($remaining_hash));
		
		$different_user->delete();
	}
	
	public function testRemoveExpiredTokens() {
		
		$user = $this->user;
		$service = $this->service;
		
		// generate mulitple tokens to test
		$tokens = [];
		$hashes = [];
		for ($i = 0; $i <= 5; $i++) {
			$token = $this->makePersistentLoginToken($user);
			$tokens[] = $token;
			$hashes[] = $this->hashToken($token);
		}
		
		// manually setting timestamp to a lower value in order for cleanup to work correctly
		// as there is no way to manipulate the timestamp during insertion
		// and cleanup prevents using a future timestamp as offset
		$qb = Update::table('users_remember_me_cookies');
		$qb->set('timestamp', 12345)
			->where($qb->compare('code', 'IN', '"' . implode('", "', $hashes) . '"'));
		_elgg_services()->db->updateData($qb);
			
		$service->removeExpiredTokens(time());
		
		foreach ($tokens as $token) {
			$hash = $this->hashToken($token);
			$this->assertNull($service->getUserFromHash($hash));
		}
		
		// again with different time offset
		// generate mulitple tokens to test
		$tokens = [];
		for ($i = 0; $i <= 5; $i++) {
			$tokens[] = $this->makePersistentLoginToken($user);
		}
		
		$service->removeExpiredTokens(time());
		
		foreach ($tokens as $token) {
			$hash = $this->hashToken($token);
			$this->assertNotNull($service->getUserFromHash($hash));
		}
	}
	
	public function setCookie(\ElggCookie $cookie) {
		$this->cookie = $cookie;
	}
	
	/**
	 * Create a service with a set cookie token in order to perform certain actions
	 *
	 * @param string $cookie_token a permanent cookie token (usualy comes from an actual cookie)
	 *
	 * @return \Elgg\PersistentLoginService
	 */
	protected function getServiceBasedOnCookie($cookie_token) {
		
		// create a service with correct cookie so we can remove it correctly
		$global_cookies_config = _elgg_services()->config->getCookieConfig();
		$cookie_config = $global_cookies_config['remember_me'];
		
		$service = new PersistentLoginService(
			_elgg_services()->db,
			_elgg_services()->session,
			_elgg_services()->crypto,
			$cookie_config,
			$cookie_token
		);
		
		$service->_callable_elgg_set_cookie = [$this, 'setCookie'];
		
		return $service;
	}
	
	/**
	 * Helper function to create a cookie token for testing
	 *
	 * @param \ElggUser $user user to create token for
	 *
	 * @return string
	 */
	protected function makePersistentLoginToken(\ElggUser $user) {
		$service = $this->service;
		
		$service->makeLoginPersistent($user);
		
		$this->assertInstanceOf(\ElggCookie::class, $this->cookie);
		
		$token = $this->cookie->value;
		$this->assertNotEmpty($token);
		
		return $token;
	}
	
	/**
	 * Hash a cookie token in order to be able to perform DB tasks
	 *
	 * @see \Elgg\PersistentLoginService::hashToken()
	 *
	 * @param string $token a cookie token to transform
	 *
	 * @return string
	 */
	protected function hashToken($token) {
		return md5($token);
	}
}
