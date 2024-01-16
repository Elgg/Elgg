<?php

namespace Elgg\Blog;

use Elgg\Plugins\IntegrationTestCase;

class CanCommentIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @dataProvider blogCommentStatusProvider
	 */
	public function testCanComment($enable_comments, $status, $expected) {
		$blog = $this->createObject([
			'subtype' => 'blog',
			'comments_on' => $enable_comments,
			'status' => $status,
		]);
		
		$this->assertInstanceOf(\ElggBlog::class, $blog);
		$this->assertFalse($blog->canComment());
		
		$user = $this->createUser();
		
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertEquals($expected, $blog->canComment());
	}
	
	public static function blogCommentStatusProvider() {
		return [
			['On', 'published', true],
			['On', 'draft', false],
			['Off', 'published', false],
			['Off', 'draft', false],
		];
	}
}
