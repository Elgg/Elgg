<?php

namespace Elgg\Actions\Comment;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class SaveIntegrationTest extends ActionResponseTestCase {
		
	public function testCommentSaveAction() {
		_elgg_services()->session_manager->setLoggedInUser($this->createUser());
		
		$entity = $this->createObject();
		
		elgg_entity_enable_capability($entity->getType(), $entity->getSubtype(), 'commentable');
		elgg_set_config('comments_max_depth', 3);
		
		$response = $this->executeAction('comment/save', [
			'entity_guid' => $entity->guid,
			'generic_comment' => 'foo',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		// validate created top comment entity
		$comments = elgg_get_entities(['type' => 'object', 'subtype' => 'comment', 'container_guid' => $entity->guid]);
		$this->assertCount(1, $comments);
		
		$top_comment = $comments[0];
		$this->assertEquals('foo', $top_comment->description);
		$this->assertEquals(1, $top_comment->level);
		$this->assertEquals(1, $top_comment->getLevel());
		$this->assertNull($top_comment->parent_guid);
		$this->assertNull($top_comment->thread_guid);
		$this->assertEquals($entity->guid, $top_comment->container_guid);
		
		// post a threaded comment
		$response = $this->executeAction('comment/save', [
			'entity_guid' => $top_comment->guid,
			'generic_comment' => 'bar',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		// validate created sub comment entity
		$comments = elgg_get_entities(['type' => 'object', 'subtype' => 'comment', 'metadata_name_value_pairs' => ['parent_guid' => $top_comment->guid]]);
		$this->assertCount(1, $comments);
		
		$sub_comment = $comments[0];
		$this->assertEquals('bar', $sub_comment->description);
		$this->assertEquals(2, $sub_comment->level);
		$this->assertEquals(2, $sub_comment->getLevel());
		$this->assertEquals($top_comment->guid, $sub_comment->parent_guid);
		$this->assertEquals($top_comment->guid, $sub_comment->thread_guid);
		$this->assertEquals($entity->guid, $sub_comment->container_guid);
		
		// post a threaded comment
		$response = $this->executeAction('comment/save', [
			'entity_guid' => $sub_comment->guid,
			'generic_comment' => 'foobar',
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		// validate created sub comment entity
		$comments = elgg_get_entities(['type' => 'object', 'subtype' => 'comment', 'metadata_name_value_pairs' => ['parent_guid' => $sub_comment->guid]]);
		$this->assertCount(1, $comments);
		
		$lowest_comment = $comments[0];
		$this->assertEquals('foobar', $lowest_comment->description);
		$this->assertEquals(3, $lowest_comment->level);
		$this->assertEquals(3, $lowest_comment->getLevel());
		$this->assertEquals($sub_comment->guid, $lowest_comment->parent_guid);
		$this->assertEquals($top_comment->guid, $lowest_comment->thread_guid);
		$this->assertEquals($entity->guid, $lowest_comment->container_guid);
		
		// post a threaded comment on unsupported level
		$response = $this->executeAction('comment/save', [
			'entity_guid' => $lowest_comment->guid,
			'generic_comment' => 'notable',
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
	}
}
