<?php

namespace Elgg\Comments;

use Elgg\IntegrationTestCase;

class ThreadPreloaderIntegrationTest extends IntegrationTestCase {

	public function testPageElementsCommentsPreloadsCommentThreads() {
		
		_elgg_services()->session_manager->setLoggedInUser($this->createUser());
		
		$entity = $this->createObject();
		
		elgg_entity_enable_capability($entity->getType(), $entity->getSubtype(), 'commentable');
		elgg_set_config('comments_max_depth', 2);
		
		$time = time();
		
		// level 1
		$top1 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 1, 'time_created' => $time - 10]);
		$top2 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 1, 'time_created' => $time - 9]);
		$top3 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 1, 'time_created' => $time - 8]);
		
		// level 2
		$sub1_1 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 2, 'parent_guid' => $top1->guid, 'thread_guid' => $top1->guid, 'time_created' => $time - 7]);
		$sub1_2 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 2, 'parent_guid' => $top1->guid, 'thread_guid' => $top1->guid, 'time_created' => $time - 6]);

		$sub2_1 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 2, 'parent_guid' => $top2->guid, 'thread_guid' => $top2->guid, 'time_created' => $time - 5]);
		
		// level 3 (preloader should not consider max depth)
		$sub1_2_1 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 3, 'parent_guid' => $sub1_2->guid, 'thread_guid' => $top1->guid, 'time_created' => $time - 4]);
		$sub1_2_2 = $this->createObject(['subtype' => 'comment', 'container_guid' => $entity->guid, 'level' => 3, 'parent_guid' => $sub1_2->guid, 'thread_guid' => $top1->guid, 'time_created' => $time - 3]);
		
		// full view of entity should show responses (which should preload threaded comments)
		elgg_view('page/elements/comments', ['entity' => $entity, 'limit' => 10]);
		
		$service = elgg()->thread_preloader;

		$children = $this->getInaccessableProperty($service, 'children');
		
		$this->assertCount(3, $children);
		$this->assertArrayHasKey($top1->guid, $children);
		$this->assertArrayHasKey($top2->guid, $children);
		$this->assertArrayHasKey($sub1_2->guid, $children);
		
		$this->assertCount(2, $children[$top1->guid]);
		$this->assertEquals($sub1_1->guid, $children[$top1->guid][0]->guid);
		$this->assertEquals($sub1_2->guid, $children[$top1->guid][1]->guid);
		
		$this->assertCount(1, $children[$top2->guid]);
		$this->assertEquals($sub2_1->guid, $children[$top2->guid][0]->guid);
		
		$this->assertCount(2, $children[$sub1_2->guid]);
		$this->assertEquals($sub1_2_1->guid, $children[$sub1_2->guid][0]->guid);
		$this->assertEquals($sub1_2_2->guid, $children[$sub1_2->guid][1]->guid);
	}
}
