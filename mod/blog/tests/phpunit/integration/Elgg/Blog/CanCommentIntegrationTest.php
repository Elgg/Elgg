<?php

namespace Elgg\Blog;

use Elgg\Plugins\PluginTesting;

/**
 * @group Plugins
 */
class CanCommentIntegrationTest extends \Elgg\IntegrationTestCase {
	
	use PluginTesting;
	
	/**
	 * @dataProvider blogCommentStatusProvider
	 */
	public function testCanComment($enable_comments, $status, $expected) {
		$blog = $this->createObject([
			'subtype' => 'blog',
		], [
			'comments_on' => $enable_comments,
			'status' => $status,
		]);
		
		$this->assertInstanceOf(\ElggBlog::class, $blog);
		$this->assertFalse($blog->canComment());
		
		$user = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user);
		
		$this->assertEquals($expected, $blog->canComment());
	}
	
	public function blogCommentStatusProvider() {
		return [
			['On', 'published', true],
			['On', 'draft', false],
			['Off', 'published', false],
			['Off', 'draft', false],
		];
	}
}
