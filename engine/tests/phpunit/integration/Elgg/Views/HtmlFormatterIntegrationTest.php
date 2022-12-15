<?php

namespace Elgg\Views;

class HtmlFormatterIntegrationTest extends \Elgg\IntegrationTestCase {
	
	protected HtmlFormatter $service;
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
			'custom_config_values' => [
				'mentions_display_format' => 'username',
			],
		]);
		
		$this->service = _elgg_services()->html_formatter;
	}
	
	/**
	 * @dataProvider mentionsProvider
	 */
	public function testParseMentions($input, $expected) {
		$result = $this->service->parseMentions($input);
		
		$this->assertEquals($expected, $result);
		
		// doing this again shouldn't change anything
		$result = $this->service->parseMentions($result);
		
		$this->assertEquals($expected, $result);
	}
	
	public function mentionsProvider() {
		$user = $this->createUser();
		$mention_url = elgg_view_url($user->getURL(), "@{$user->username}");
		
		return [
			[
				'input' => "Foo @{$user->username} bar",
				'expected' => "Foo {$mention_url} bar",
			],
			[
				'input' => "@{$user->username} bar",
				'expected' => "{$mention_url} bar",
			],
			[
				'input' => "@@{$user->username} bar", // double @
				'expected' => "@@{$user->username} bar",
			],
			[
				'input' => "Foo @{$user->username}. bar", // dot behind username
				'expected' => "Foo {$mention_url}. bar",
			],
			[
				'input' => "Foo @{$user->username}&nbsp;", // & behind username, sometimes added by ckeditor.
				'expected' => "Foo {$mention_url}&nbsp;",
			],
			[
				'input' => "Foo @unknown_username bar", // should not replace anything
				'expected' => "Foo @unknown_username bar",
			],
			[
				'input' => "Foo me@{$user->username}.com bar", // email with username domain ????
				'expected' => "Foo me@{$user->username}.com bar",
			],
			[
				'input' => "Foo @{$user->username}; bar", // invalid username
				'expected' => "Foo @{$user->username}; bar",
			],
			[
				'input' => "Foo <a>@{$user->username}</a> bar", // already wrapped in anchor
				'expected' => "Foo <a>@{$user->username}</a> bar",
			],
			[
				'input' => "Foo <span>@{$user->username}</span> bar", // wrapped in non-anchor
				'expected' => "Foo <span>{$mention_url}</span> bar",
			],
			[
				'input' => "Foo <span data-username='@{$user->username}'>lorem</span> bar", // as part of an html attribute
				'expected' => "Foo <span data-username='@{$user->username}'>lorem</span> bar",
			],
		];
	}
}
