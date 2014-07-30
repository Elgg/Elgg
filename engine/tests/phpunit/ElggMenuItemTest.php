<?php

/**
 * Depends on elgg_normalize_url() in output.php
 */
class ElggMenuItemTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
	}

	public function testFactoryNoNameOrText() {
		$this->assertNull(\ElggMenuItem::factory(array('name' => 'test')));
		$this->assertNull(\ElggMenuItem::factory(array('text' => 'test')));
	}

	public function testFactoryNoHref() {
		$item = \ElggMenuItem::factory(array('name' => 'test','text' => 'test'));
		$this->assertEquals('', $item->getHref());
	}

	public function testFactoryAllOptions() {
		$params = array(
			'name' => 'thename',
			'text' => 'menu',
			'href' => 'test',
			'title' => 'tooltip',
			'confirm' => 'delete?',
			'contexts' => array('blog', 'bookmarks'),
			'link_class' => 'elgg-link',
			'item_class' => 'elgg-item',
			'section' => 'main',
			'priority' => 50,
			'selected' => true,
			'parent_name' => 'node',
		);
		$item = \ElggMenuItem::factory($params);

		$this->assertEquals($params['name'], $item->getName());
		$this->assertEquals($params['text'], $item->getText());
		$this->assertEquals(elgg_normalize_url($params['href']), $item->getHref());
		$this->assertEquals($params['title'], $item->getTooltip());
		$this->assertEquals($params['confirm'], $item->getConfirmText());
		$this->assertEquals($params['contexts'], $item->getContext());
		$this->assertEquals($params['link_class'], $item->getLinkClass());
		$this->assertEquals("elgg-menu-item-{$params['name']} {$params['item_class']}", $item->getItemClass());
		$this->assertEquals($params['section'], $item->getSection());
		$this->assertEquals($params['priority'], $item->getPriority());
		$this->assertEquals($params['selected'], $item->getSelected());
		$this->assertEquals($params['parent_name'], $item->getParentName());
	}

	public function testFactorySetData() {
		$params = array(
			'name' => 'thename',
			'text' => 'menu',
			'href' => 'test',
			'data' => array(
				'section' => 'main',
				'priority' => 50,
				'selected' => true,
				'parent_name' => 'node',
			),
		);
		$item = \ElggMenuItem::factory($params);

		$this->assertEquals($params['data']['section'], $item->getSection());
		$this->assertEquals($params['data']['priority'], $item->getPriority());
		$this->assertEquals($params['data']['selected'], $item->getSelected());
		$this->assertEquals($params['data']['parent_name'], $item->getParentName());
	}

	public function testFactoryContextShortcut() {
		$params = array(
			'name' => 'thename',
			'text' => 'menu',
			'href' => 'test',
			'context' => array('blog', 'bookmarks'),
		);
		$item = \ElggMenuItem::factory($params);

		$this->assertEquals($params['context'], $item->getContext());
	}

	public function testConstructorUrlNormalization() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$url = elgg_normalize_url('url');
		$this->assertEquals($url, $item->getHref());
	}

	public function testSetDataWithSingleValue() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setData('foo', 'value');
		$this->assertEquals('value', $item->getData('foo'));
		$item->setData('name', 'new_name');
		$this->assertEquals('new_name', $item->getData('name'));
		$item->setData('name', 'new_name_again');
		$this->assertEquals('new_name_again', $item->getData('name'));
	}

	public function testSetDataWithArray() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setData(array(
			'priority' => 88,
			'new' => 64,
		));
		$this->assertEquals(88, $item->getData('priority'));
		$this->assertEquals(88, $item->getPriority());
		$this->assertEquals(64, $item->getData('new'));
	}

	public function testGetDataNonExistentKey() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$this->assertNull($item->getData('blah'));
	}

	public function testSetContextWithString() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setContext('mine');
		$this->assertEquals(array('mine'), $item->getContext());
	}

	public function testSetContextWithArray() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setContext(array('mine'));
		$this->assertEquals(array('mine'), $item->getContext());
	}

	public function testInContextAll() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setContext('all');
		$this->assertTrue($item->inContext('blog'));
		$this->assertTrue($item->inContext(''));
	}

	public function testInContextWithParticularContext() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setContext(array('blog', 'bookmarks'));
		$this->assertTrue($item->inContext('blog'));
		$this->assertFalse($item->inContext('file'));
	}

/*
 * This requires elgg_in_context()
	public function testInContextAgainstRequestContext() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setContext(array('blog', 'bookmarks'));
	}
*/

	public function testSetLinkClassWithString() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setLinkClass('elgg-link');
		$this->assertEquals('elgg-link', $item->getLinkClass());
		$item->setLinkClass('new-link');
		$this->assertEquals('new-link', $item->getLinkClass());
	}

	public function testSetLinkClassWithArray() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setLinkClass(array('elgg-link', '2nd-link'));
		$this->assertEquals('elgg-link 2nd-link', $item->getLinkClass());
	}

	public function testAddLinkClassWithString() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->addLinkClass('new-link');
		$item->addLinkClass('2nd-link');
		$this->assertEquals('new-link 2nd-link', $item->getLinkClass());

		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setLinkClass('new-link');
		$item->addLinkClass('2nd-link');
		$this->assertEquals('new-link 2nd-link', $item->getLinkClass());
	}

	public function testAddLinkClassWithArray() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setLinkClass('new-link');
		$item->addLinkClass(array('2nd-link'));
		$this->assertEquals('new-link 2nd-link', $item->getLinkClass());
	}

	public function testGetItemClass() {
		$item = new \ElggMenuItem('name', 'text', 'url');
		$item->setItemClass('new-link');
		$item->addItemClass(array('2nd-link'));
		$this->assertEquals('elgg-menu-item-name new-link 2nd-link', $item->getItemClass());
	}

	public function testGetItemClassNormalizeName() {
		$item = new \ElggMenuItem('name_underscore', 'text', 'url');
		$this->assertEquals('elgg-menu-item-name-underscore', $item->getItemClass());
		$item = new \ElggMenuItem('name space', 'text', 'url');
		$this->assertEquals('elgg-menu-item-name-space', $item->getItemClass());
		$item = new \ElggMenuItem('name:colon', 'text', 'url');
		$this->assertEquals('elgg-menu-item-name-colon', $item->getItemClass());
	}
}
