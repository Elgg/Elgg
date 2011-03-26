<?php
/**
 * Regular expression tests for the wire
 */
class TheWireRegexTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		$this->ia = elgg_set_ignore_access(TRUE);
		parent::__construct();

		// all __construct() code should come after here
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		elgg_set_ignore_access($this->ia);
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Get the link for a user's wire page
	 *
	 * @param string $username Username
	 * @return string
	 */
	protected function getUserWireLink($username) {
		$url = "thewire/owner/$username";
		$url = elgg_normalize_url($url);
		return "<a href=\"$url\">@$username</a>";
	}

	/**
	 * Get the link for a hashtag page
	 *
	 * @param string $tag Tag string
	 * @return string
	 */
	protected function getHashtagLink($tag) {
		$url = "thewire/tag/$tag";
		$url = elgg_normalize_url($url);
		return "<a href=\"$url\">#$tag</a>";
	}

	/**
	 * Get a link for an email address mailto
	 *
	 * @param string $address Email address
	 * @return string
	 */
	protected function getEmailLink($address) {
		return "<a href=\"mailto:$address\">$address</a>";
	}

	/**
	 * Get the html for a link
	 *
	 * @param string $address URL
	 * @return string
	 */
	protected function getLink($address) {
		return parse_urls($address);
	}

	/**
	 * Usernames: @user
	 */
	public function testReplaceUsernames() {
		// beginning of text
		$text = "@user test";
		$expected = $this->getUserWireLink('user') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// after space
		$text = "test @user test";
		$expected = "test " . $this->getUserWireLink('user') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by comma
		$text = "test @user, test";
		$expected = "test " . $this->getUserWireLink('user') . ", test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);
		
		// preceded by comma
		$text = "test ,@user test";
		$expected = "test ," . $this->getUserWireLink('user') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// include digit
		$text = "@3user test";
		$expected = $this->getUserWireLink('3user') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// include underscore
		$text = "@user_name test";
		$expected = $this->getUserWireLink('user_name') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// parentheses
		$text = "test (@user) test";
		$expected = "test (" . $this->getUserWireLink('user') . ") test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);
	}

	/**
	 * Hashtags: #tag
	 */
	public function testReplaceHashtags() {
		// tag at beginning
		$text = "#tag test";
		$expected = $this->getHashtagLink('tag') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// tag not at beginning
		$text = "test #tag test";
		$expected = "test " . $this->getHashtagLink('tag') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by comma
		$text = "test #tag, test";
		$expected = "test " . $this->getHashtagLink('tag') . ", test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// preceded by comma
		$text = "test,#tag test";
		$expected = "test," . $this->getHashtagLink('tag') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by period
		$text = "test #tag. test";
		$expected = "test " . $this->getHashtagLink('tag') . ". test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// parentheses
		$text = "test (#tag) test";
		$expected = "test (" . $this->getHashtagLink('tag') . ") test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// include number
		$text = "test #tag2000 test";
		$expected = "test " . $this->getHashtagLink('tag2000') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// cannot be just a number
		$text = "test #1 test";
		$expected = $text;
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);
}

	/**
	 * Email: johndoe@gmail.com
	 */
	public function testReplaceEmailAddress() {
		// email at beginning of text
		$text = "test@test.com test";
		$expected = $this->getEmailLink('test@test.com') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// after space
		$text = "test test@test.com test";
		$expected = "test " . $this->getEmailLink('test@test.com') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by comma
		$text = "test test@test.com, test";
		$expected = "test " . $this->getEmailLink('test@test.com') . ", test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// preceded by comma
		$text = "test,test@test.com test";
		$expected = "test," . $this->getEmailLink('test@test.com') . " test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by period
		$text = "test test@test.com. test";
		$expected = "test " . $this->getEmailLink('test@test.com') . ". test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// parentheses
		$text = "test (test@test.com) test";
		$expected = "test (" . $this->getEmailLink('test@test.com') . ") test";
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// includes digits
		$text = "user1@domain1.com";
		$expected = $this->getEmailLink('user1@domain1.com');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// includes underscore
		$text = "user_name@domain.com";
		$expected = $this->getEmailLink('user_name@domain.com');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// includes period
		$text = "user.name@domain.com";
		$expected = $this->getEmailLink('user.name@domain.com');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// includes subdomains
		$text = "user.name@domain.com.uk";
		$expected = $this->getEmailLink('user.name@domain.com.uk');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);
	}

	/**
	 * Links: http://www.example.org/
	 */
	public function testReplaceLinks() {
		// beginning of text
		$text = "http://www.test.org";
		$expected = $this->getLink('http://www.test.org');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// not at beginning of text
		$text = "test http://www.test.org";
		$expected = 'test ' . $this->getLink('http://www.test.org');
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by comma
		$text = "test http://www.test.org, test";
		$expected = 'test ' . $this->getLink('http://www.test.org') . ', test';
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// preceeded by comma
		$text = "test,http://www.test.org test";
		$expected = 'test,' . $this->getLink('http://www.test.org') . ' test';
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// followed by period
		$text = "test http://www.test.org. test";
		$expected = 'test ' . $this->getLink('http://www.test.org') . '. test';
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// surrounded by parentheses
		$text = "test (http://www.test.org) test";
		$expected = 'test (' . $this->getLink('http://www.test.org') . ') test';
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);

		// no http://
		$text = "test www.test.org test";
		$expected = 'test ' . $this->getLink('www.test.org') . ' test';
		$result = thewire_filter($text);
		$this->assertEqual($result, $expected);
	}

}
