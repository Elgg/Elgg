<?php

namespace Elgg\TheWire;

use Elgg\IntegrationTestCase;

class RegexIntegrationTest extends IntegrationTestCase {

	/**
	 * Get the link for a user's mention
	 *
	 * @param string $text the text to replace mentions in
	 *
	 * @return string
	 */
	protected function getMentionLink($text) {
		return elgg_parse_mentions($text);
	}

	/**
	 * Get the link for a hashtag page
	 *
	 * @param string $tag Tag string
	 *
	 * @return string
	 */
	protected function getHashtagLink($tag) {
		$url = elgg_generate_url('collection:object:thewire:tag', [
			'tag' => $tag,
		]);
		return elgg_view_url($url, "#{$tag}");
	}

	/**
	 * Get a link for an email address mailto
	 *
	 * @param string $address Email address
	 *
	 * @return string
	 */
	protected function getEmailLink($address) {
		return elgg_parse_emails($address);
	}

	/**
	 * Get the html for a link
	 *
	 * @param string $address URL
	 *
	 * @return string
	 */
	protected function getLink($address) {
		return elgg_parse_urls($address);
	}
	
	/**
	 * @dataProvider filterDataProvider
	 */
	public function testTheWireFilter($input, $expected) {
		$this->assertEquals($expected, thewire_filter($input));
	}
	
	public function filterDataProvider() {
		$user = $this->createUser();
		$user_digit = $this->createUser([
			'username' => 'username' . time(),
		]);
		$user_underscore = $this->createUser([
			'username' => 'username_' . time(),
		]);
		$user_utf8 = $this->createUser([
			'username' => 'tyúkanyó' . time(),
		]);
		
		return [
			// usernames
			["@{$user->username} test", $this->getMentionLink("@{$user->username} test")], // beginning of text
			["test @{$user->username} test", $this->getMentionLink("test @{$user->username} test")], // after space
			["test @{$user->username}, test", $this->getMentionLink("test @{$user->username}, test")], // followed by comma
			["test ,@{$user->username} test", $this->getMentionLink("test ,@{$user->username} test")], // preceded by comma
			["@{$user_digit->username} test", $this->getMentionLink("@{$user_digit->username} test")], // include digit
			["@{$user_underscore->username} test", $this->getMentionLink("@{$user_underscore->username} test")], // include underscore
			["test (@{$user->username}) test", $this->getMentionLink("test (@{$user->username}) test")], // parentheses
			["@{$user_utf8->username} test", $this->getMentionLink("@{$user_utf8->username} test")], // utf8 characters
			
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
