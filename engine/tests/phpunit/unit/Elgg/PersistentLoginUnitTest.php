<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class PersistentLoginUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $dbMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $cryptoMock;

	/**
	 * @var \Elgg\PersistentLoginService
	 */
	protected $svc;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $user123;

	/**
	 * @var \ElggCookie
	 */
	protected $lastCookieSet;

	/**
	 * @var string
	 */
	protected $mockToken;

	/**
	 * @var string
	 */
	protected $mockHash;

	/**
	 * @var int
	 */
	protected $timeSlept;

	/**
	 * @var int
	 */
	protected $thirtyDaysAgo;

	public function up() {
		$this->thirtyDaysAgo = strtotime("-30 days");

		$this->mockToken = 'z' . str_repeat('a', 31);

		$this->mockHash = md5($this->mockToken);

		$this->user123 = $this->getMockElggUser(123);

		$this->session = \ElggSession::getMock();

		// mock DB
		$this->dbMock = $this->getMockBuilder('\Elgg\Database')
			->disableOriginalConstructor()
			->getMock();
		
		$this->cryptoMock = $this->getMockBuilder('\ElggCrypto')
			->getMock();
		$this->cryptoMock->expects($this->any())
			->method('getRandomString')
			->will($this->returnValue(str_repeat('a', 31)));

		$this->svc = $this->getSvcWithCookie("");
	}

	public function down() {

	}

	function testLoginSavesHashAndPutsTokenInCookieAndSession() {
		$this->dbMock->expects($this->once())
				->method('insertData')
				->will($this->returnCallback(array($this, 'mock_insertData')));

		$this->svc->makeLoginPersistent($this->user123);

		$this->assertSame($this->mockToken, $this->lastCookieSet->value);
		$this->assertSame($this->mockToken, $this->session->get('code'));
	}

	function testRemoveDeletesHashAndDeletesTokenFromCookieAndSession() {
		$this->svc = $this->getSvcWithCookie($this->mockToken);

		$this->dbMock->expects($this->once())
				->method('deleteData')
				->will($this->returnCallback(array($this, 'mock_deleteData')));

		$this->svc->removePersistentLogin();

		$this->assertSame('', $this->lastCookieSet->value);
		$this->assertSame($this->thirtyDaysAgo, $this->lastCookieSet->expire);
		$this->assertNull($this->session->get('code'));
	}

	function testRemoveWithoutCookieCantDeleteHash() {
		$this->dbMock->expects($this->never())
				->method('deleteData');

		$this->svc->removePersistentLogin();

		$this->assertSame('', $this->lastCookieSet->value);
		$this->assertSame($this->thirtyDaysAgo, $this->lastCookieSet->expire);
		$this->assertNull($this->session->get('code'));
	}

	function testGettingUserFromKnownHashReturnsUser() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnCallback(array($this, 'mock_getDataRow')));

		$user = $this->svc->getUserFromHash($this->mockHash);

		$this->assertSame($this->user123, $user);
	}

	function testGettingUserFromMissingHashReturnsNull() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnValue(array()));

		$user = $this->svc->getUserFromHash($this->mockHash);

		$this->assertNull($user);
	}

	function testGettingMissingUserFromKnownHashReturnsNull() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnValue((object) array('guid' => 234)));

		$user = $this->svc->getUserFromHash($this->mockHash);

		$this->assertNull($user);
	}

	function testChangingOwnPasswordDeletesAllHashesAndMakesPersistent() {
		$subject = $this->user123;
		$modifier = $this->user123;

		$this->dbMock->expects($this->exactly(2))
				->method('deleteData');
		// Here we can't make an expectation on mock_deleteAll because one
		// of the calls deletes all, and another deletes only a single hash.
		// We'd have to fix mock_deleteAll to handle it.
		// @todo replace this with a real DB test

		$this->dbMock->expects($this->once())
				->method('insertData')
				->will($this->returnCallback(array($this, 'mock_insertData')));

		$this->svc = $this->getSvcWithCookie('notempty');
		$this->svc->handlePasswordChange($subject, $modifier);

		$this->assertSame($this->mockToken, $this->lastCookieSet->value);
		$this->assertSame($this->mockToken, $this->session->get('code'));
	}

	function testChangingOwnPasswordWithNoCookieDoesntMakePersistent() {
		$subject = $this->user123;
		$modifier = $this->user123;

		$this->dbMock->expects($this->once())
				->method('deleteData')
				->will($this->returnCallback(array($this, 'mock_deleteAll')));
		$this->dbMock->expects($this->never())
				->method('insertData');

		$this->svc->handlePasswordChange($subject, $modifier);

		$this->assertNull($this->lastCookieSet);
		$this->assertNull($this->session->get('code'));
	}

	function testChangingSomeoneElsesPasswordDoesntMakePersistent() {
		$subject = $this->user123;
		$modifier = $this->getMockElggUser(234);

		$this->dbMock->expects($this->atLeastOnce())
				->method('deleteData')
				->will($this->returnCallback(array($this, 'mock_deleteAll')));
		$this->dbMock->expects($this->never())
				->method('insertData');

		$this->svc->handlePasswordChange($subject, $modifier);

		$this->assertNull($this->lastCookieSet);
		$this->assertNull($this->session->get('code'));
	}

	function testGettingUserFromValidClientReturnsUser() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnValue((object) array('guid' => 123)));

		$this->svc = $this->getSvcWithCookie($this->mockToken);

		$user = $this->svc->bootSession();

		$this->assertSame($this->user123, $user);
	}

	function testGetPersistedUser_invalidModernToken() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnValue(array()));

		$this->svc = $this->getSvcWithCookie('z' . str_repeat('b', 31));

		$user = $this->svc->bootSession();

		$this->assertNull($this->timeSlept);
		$this->assertSame('', $this->lastCookieSet->value);
		$this->assertSame($this->thirtyDaysAgo, $this->lastCookieSet->expire);
		$this->assertNull($user);
	}

	function testBootSessionWithInvalidLegacyTokenCausesDelayAndFailure() {
		$this->dbMock->expects($this->once())
				->method('getDataRow')
				->will($this->returnValue(array()));

		$this->svc = $this->getSvcWithCookie(str_repeat('b', 32));

		$user = $this->svc->bootSession();

		$this->assertSame(1, $this->timeSlept);
		$this->assertSame('', $this->lastCookieSet->value);
		$this->assertSame($this->thirtyDaysAgo, $this->lastCookieSet->expire);
		$this->assertNull($user);
	}

	function testReplaceLegacyTokenWithNoCookieDoesNothing() {
		$this->svc = $this->getSvcWithCookie('');

		$this->dbMock->expects($this->never())
				->method('deleteData');

		$this->svc->replaceLegacyToken($this->user123);

		$this->assertNull($this->lastCookieSet);
		$this->assertNull($this->session->get('code'));
	}

	function testModernTokenCookiesAreNotReplaced() {
		$this->dbMock->expects($this->never())
				->method('deleteData');

		$this->svc->replaceLegacyToken($this->user123);

		$this->assertNull($this->lastCookieSet);
		$this->assertNull($this->session->get('code'));
	}

	function testLegacyCookiesAreReplacedInDbCookieAndSession() {
		$this->svc = $this->getSvcWithCookie(str_repeat('a', 32));

		$this->dbMock->expects($this->atLeastOnce())
				->method('deleteData');
		$this->dbMock->expects($this->once())
				->method('insertData');

		$this->svc->replaceLegacyToken($this->user123);

		$this->assertSame($this->mockToken, $this->lastCookieSet->value);
		$this->assertSame($this->mockToken, $this->session->get('code'));
	}
	
	function testUpdateTokenUsageWithoutCookie() {
		$this->assertNull($this->svc->updateTokenUsage($this->user123));
	}
	
	function testUpdateTokenUsageWithWrongUser() {
		$this->svc = $this->getSvcWithCookie($this->mockToken);
		
		$wrong_user = $this->createUser();
		
		$this->dbMock->expects($this->once())
				->method('updateData')
				->will($this->returnCallback([$this, 'mock_updateWrongUser']));
		
		$this->assertFalse($this->svc->updateTokenUsage($wrong_user));
	}
	
	function testUpdateTokenUsageWithCorrectUser() {
		$this->svc = $this->getSvcWithCookie($this->mockToken);
		
		$this->dbMock->expects($this->once())
				->method('updateData')
				->will($this->returnCallback([$this, 'mock_updateCorrectUser']));
		
		$this->assertTrue($this->svc->updateTokenUsage($this->user123));
		
		$this->assertSame($this->mockToken, $this->lastCookieSet->value);
	}
	
	function testRemoveExpiredTokens() {
		$this->dbMock->expects($this->once())
				->method('deleteData')
				->will($this->returnCallback([$this, 'mock_deleteExpiredTokens']));
		
		$this->assertTrue($this->svc->removeExpiredTokens(time()));
	}
	
	function testRemoveAllHashes() {
		$this->dbMock->expects($this->once())
				->method('deleteData')
				->will($this->returnCallback([$this, 'mock_deleteAll']));
		
		$this->svc->removeAllHashes($this->user123);
	}

	// mock \ElggUser which will return the GUID on ->guid reads
	function getMockElggUser($guid) {
		$user = $this->getMockBuilder('\ElggUser')
				->disableOriginalConstructor()
				->getMock();
		$user->expects($this->any())
				->method('__get')
				->with('guid')
				->will($this->returnValue((int) $guid));
		return $user;
	}

	function mock_get_user($guid) {
		if ((int) $guid === 123) {
			return $this->user123;
		}
		return null;
	}

	function mock_elgg_set_cookie(\ElggCookie $cookie) {
		$this->lastCookieSet = $cookie;
	}

	function mock_sleep($seconds) {
		$this->timeSlept = $seconds;
	}

	/**
	 * @param string $cookie_token
	 * @return \Elgg\PersistentLoginService
	 */
	protected function getSvcWithCookie($cookie_token = '') {
		$cookie_config = array(
			'lifetime' => 0,
			'path' => '/',
			'domain' => '',
			'secure' => false,
			'httponly' => false,
			'name' => 'elggperm',
			'expire' => time() + (30 * 86400),
		);
		$time = $this->thirtyDaysAgo + (30 * 86400);
		$svc = new \Elgg\PersistentLoginService(
				$this->dbMock, $this->session, $this->cryptoMock, $cookie_config, $cookie_token, $time);

		$svc->_callable_get_user = array($this, 'mock_get_user');
		$svc->_callable_generateToken = array($this, 'mock_generateToken');
		$svc->_callable_elgg_set_cookie = array($this, 'mock_elgg_set_cookie');
		$svc->_callable_sleep = array($this, 'mock_sleep');
		return $svc;
	}

	function mock_insertData($sql, $params) {
		$pattern = '~INSERT INTO users_remember_me_cookies \(code, guid, timestamp\)\\s+VALUES \(:hash, :guid, :time\)~';
		$this->assertRegExp($pattern, $sql);
		$this->assertArraySubset([
			':guid' => 123,
			':hash' => $this->mockHash,
		], $params);
	}

	function mock_deleteData($sql, $params) {
		$pattern = '~DELETE FROM users_remember_me_cookies\\s+WHERE code = :hash~';
		$this->assertRegExp($pattern, $sql);
		$this->assertEquals([
			':hash' => $this->mockHash,
		], $params);
	}

	function mock_getDataRow($sql, $callback, $params) {
		$pattern = '~SELECT guid\\s+FROM users_remember_me_cookies\\s+WHERE code = :hash~';
		$this->assertRegExp($pattern, $sql);
		$this->assertEquals([
			':hash' => $this->mockHash,
		], $params);

		return (object) array('guid' => 123);
	}

	function mock_deleteAll($sql, $params) {
		$pattern = '~DELETE FROM users_remember_me_cookies\\s+WHERE guid = :guid~';
		$this->assertRegExp($pattern, $sql);
		$this->assertEquals([
			':guid' => 123,
		], $params);
	}

	function mock_updateWrongUser($sql, $get_num_rows, $params) {
		$pattern = '~UPDATE users_remember_me_cookies\\s+SET timestamp = :time\\s+WHERE guid = :guid\\s+AND code = :hash~';
		$this->assertRegExp($pattern, $sql);
		$this->assertArraySubset([
			':hash' => $this->mockHash,
		], $params);
		$this->assertNotContains($params, [
			':guid' => 123,
		]);
	}
	
	function mock_updateCorrectUser($sql, $get_num_rows, $params) {
		$pattern = '~UPDATE users_remember_me_cookies\\s+SET timestamp = :time\\s+WHERE guid = :guid\\s+AND code = :hash~';
		$this->assertRegExp($pattern, $sql);
		$this->assertArraySubset([
			':guid' => 123,
			':hash' => $this->mockHash,
		], $params);
		
		return 1;
	}
	
	function mock_deleteExpiredTokens($sql, $params) {
		$pattern = '~DELETE FROM users_remember_me_cookies\\s+WHERE timestamp < :time~';
		$this->assertRegExp($pattern, $sql);
		$this->assertArrayHasKey(':time', $params);
		
		return 1;
	}
}
