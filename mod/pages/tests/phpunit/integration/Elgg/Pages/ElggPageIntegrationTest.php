<?php

namespace Elgg\Pages;

use ElggPage;

/**
 * Integration test for ElggPage
 */
class ElggPageIntegrationTest extends \Elgg\IntegrationTestCase {
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * @var ElggPage
	 */
	protected $top_page;
	
	/**
	 * @var ElggPage
	 */
	protected $page;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->user = $this->createUser();
		
		$session = elgg_get_session();
		$session->setLoggedInUser($this->user);
		
		// create a top page
		$top_page = new ElggPage();
		$top_page->owner_guid = $this->user->guid;
		$top_page->container_guid = $this->user->guid;
		$top_page->title = 'Test ElggPage top';
		$top_page->description = 'This is a test for ElggPage';
		
		$this->assertTrue($top_page->save());
		$this->top_page = $top_page;
		
		$page = new ElggPage();
		$page->owner_guid = $this->user->guid;
		$page->container_guid = $this->user->guid;
		$page->title = 'Test ElggPage sub';
		$page->description = 'This is a test for ElggPage which is a sub page';
		$page->parent_guid = $this->top_page->guid;
		
		$this->assertTrue($page->save());
		$this->page = $page;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {

		if (isset($this->top_page)) {
			$this->top_page->delete();
		}
		
		if (isset($this->page)) {
			$this->page->delete();
		}
		
		$session = elgg_get_session();
		$session->setLoggedInUser($this->getAdmin());
		
		if (isset($this->user)) {
			$this->user->delete();
		}
		
		$session->removeLoggedInUser();
	}

	public function testCreateObjectToClass() {
		
		/* @var $page \ElggPage */
		$page = $this->createObject([
			'subtype' => 'page',
			'owner_guid' => $this->user->guid,
			'container_guid' => $this->user->guid,
			'title' => 'Test ElggPage',
			'description' => 'This is a test for ElggPage',
		]);
		
		$this->assertInstanceOf(ElggPage::class, $page);
		$this->assertNotEmpty($page->guid);
		$this->assertEquals($this->user->guid, $page->owner_guid);
		$this->assertEquals($this->user->guid, $page->container_guid);
		$this->assertEquals('page', $page->getSubtype());
	}
	
	public function testIsTopPage() {
		
		$top_page = $this->top_page;
		
		$this->assertInstanceOf(ElggPage::class, $top_page);
		$this->assertTrue($top_page->isTopPage());
		
		$page = $this->page;
		
		$this->assertInstanceOf(ElggPage::class, $page);
		$this->assertFalse($page->isTopPage());
	}
	
	public function testGetParentEntity() {
		
		$top_page = $this->top_page;
		$page = $this->page;
		
		$this->assertInstanceOf(ElggPage::class, $top_page);
		$this->assertInstanceOf(ElggPage::class, $page);
		
		$this->assertFalse($top_page->getParentEntity());
		$this->assertEquals($page->getParentEntity()->guid, $top_page->guid);
		
		// invalid parent page (non ElggPage guid)
		$page->parent_guid = $this->user->guid;
		
		$this->assertEquals($this->user->guid, $page->parent_guid);
		$this->assertFalse($page->getParentEntity());
		
		// invalid parent page (non guid)
		$page->parent_guid = 'a';
		
		$this->assertEquals('a', $page->parent_guid);
		$this->assertFalse($page->getParentEntity());
		
		// invalid parent page (no metadata)
		$page->parent_guid = null;
		
		$this->assertNull($page->parent_guid);
		$this->assertFalse($page->getParentEntity());
	}
	
	public function testGetParentGUID() {
		
		$top_page = $this->top_page;
		$page = $this->page;
		
		$this->assertInstanceOf(ElggPage::class, $top_page);
		$this->assertInstanceOf(ElggPage::class, $page);
		
		$this->assertEquals(0, $top_page->getParentGUID());
		$this->assertEquals($top_page->guid, $page->getParentGUID());
		
		// invalid parent page (non ElggPage guid)
		$page->parent_guid = $this->user->guid;
		
		$this->assertEquals($this->user->guid, $page->parent_guid);
		$this->assertEquals($this->user->guid, $page->getParentGUID());
		
		// invalid parent page (non guid)
		$page->parent_guid = 'a';
		
		$this->assertEquals('a', $page->parent_guid);
		$this->assertNotEquals('a', $page->getParentGUID());
		
		// invalid parent page (no metadata)
		$page->parent_guid = null;
		
		$this->assertNull($page->parent_guid);
		$this->assertEquals(0, $page->getParentGUID());
	}
	
	public function testSetParentByGUID() {
		
		$top_page = $this->top_page;
		$page = $this->page;
		
		$this->assertInstanceOf(ElggPage::class, $top_page);
		$this->assertInstanceOf(ElggPage::class, $page);
		
		// set to 0
		$this->assertTrue($page->setParentByGUID(0));
		$this->assertEquals(0, $page->getParentGUID());
		
		// set to valid ElggPage
		$this->assertTrue($page->setParentByGUID($top_page->guid));
		$this->assertEquals($top_page->guid, $page->getParentGUID());
		
		// set to non ElggPage
		$this->assertFalse($page->setParentByGUID($this->user->guid));
		$this->assertNotEquals($this->user->guid, $page->getParentGUID());
	}
	
	public function testSetParentEntity() {
		
		$top_page = $this->top_page;
		$page = $this->page;
		
		$this->assertInstanceOf(ElggPage::class, $top_page);
		$this->assertInstanceOf(ElggPage::class, $page);
		
		// set to empty
		$this->assertTrue($page->setParentEntity(null));
		$this->assertEquals(0, $page->getParentGUID());
		
		// set to valid ElggPage
		$this->assertTrue($page->setParentEntity($top_page));
		$this->assertEquals($top_page->guid, $page->getParentGUID());
		
		// set to non ElggPage
		$this->assertFalse($page->setParentEntity($this->user));
		$this->assertNotEquals($this->user->guid, $page->getParentGUID());
	}
}
