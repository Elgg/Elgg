<?php

namespace Elgg\TheWire;

use Elgg\IntegrationTestCase;

class RegexIntegrationTest extends IntegrationTestCase {

	public function up() {
		if (!elgg_is_active_plugin('thewire')) {
			$this->markTestSkipped();
		}
	}

	public function down() {
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
	 * @dataProvider filterDataProvider
	 */
	public function testTheWireFilter($input, $expected) {
		$this->assertEquals($expected, thewire_filter($input));
	}
	
	public function filterDataProvider() {
		return [
			// usernames
			["@user test", $this->getUserWireLink('user') . " test"], // beginning of text
			["test @user test", "test " . $this->getUserWireLink('user') . " test"], // after space
			["test @user, test", "test " . $this->getUserWireLink('user') . ", test"], // followed by comma
			["test ,@user test", "test ," . $this->getUserWireLink('user') . " test"], // preceded by comma
			["@3user test", $this->getUserWireLink('3user') . " test"], // include digit
			["@user_name test", $this->getUserWireLink('user_name') . " test"], // include underscore
			["test (@user) test", "test (" . $this->getUserWireLink('user') . ") test"], // parentheses
			["@tyúkanyó", $this->getUserWireLink('tyúkanyó')], // utf8 characters
			
			// hashtags
			["#tag test", $this->getHashtagLink('tag') . " test"], // tag at beginning
			["test #tag test", "test " . $this->getHashtagLink('tag') . " test"], // tag not at beginning
			["test #tag, test", "test " . $this->getHashtagLink('tag') . ", test"], // followed by comma
			["test,#tag test", "test," . $this->getHashtagLink('tag') . " test"], // preceded by comma
			["test #tag. test", "test " . $this->getHashtagLink('tag') . ". test"], // followed by period
			["test (#tag) test", "test (" . $this->getHashtagLink('tag') . ") test"], // parentheses
			["test #tag2000 test", "test " . $this->getHashtagLink('tag2000') . " test"], // include number
			["test #1 test", "test #1 test"], // cannot be just a number
			
			// emailaddress
			["test@test.com test", $this->getEmailLink('test@test.com') . " test"], // email at beginning of text
			["test test@test.com test", "test " . $this->getEmailLink('test@test.com') . " test"], // after space
			["test test@test.com, test", "test " . $this->getEmailLink('test@test.com') . ", test"], // followed by comma
			["test,test@test.com test", "test," . $this->getEmailLink('test@test.com') . " test"], // preceded by comma
			["test test@test.com. test", "test " . $this->getEmailLink('test@test.com') . ". test"], // followed by period
			["test (test@test.com) test", "test (" . $this->getEmailLink('test@test.com') . ") test"], // parentheses
			["user1@domain1.com", $this->getEmailLink('user1@domain1.com')], // includes digits
			["user_name@domain.com", $this->getEmailLink('user_name@domain.com')], // includes underscore
			["user.name@domain.com", $this->getEmailLink('user.name@domain.com')], // includes period
			["user.name@domain.com.uk", $this->getEmailLink('user.name@domain.com.uk')], // includes subdomains
			
			// links
			["http://www.test.org", $this->getLink('http://www.test.org')], // beginning of text
			["test http://www.test.org", 'test ' . $this->getLink('http://www.test.org')], // not at beginning of text
			["test http://www.test.org, test", 'test ' . $this->getLink('http://www.test.org') . ', test'], // followed by comma
			["test,http://www.test.org test", 'test,' . $this->getLink('http://www.test.org') . ' test'], // preceeded by comma
			["test http://www.test.org. test", 'test ' . $this->getLink('http://www.test.org') . '. test'], // followed by period
			["test (http://www.test.org) test", 'test (' . $this->getLink('http://www.test.org') . ') test'], // surrounded by parentheses
			["test www.test.org test", 'test ' . $this->getLink('www.test.org') . ' test'], // no http://
		];
	}
}
