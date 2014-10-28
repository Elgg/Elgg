<?php
/**
 * If you make a change in the tests for native storage, update the tests for mock storage
 *
 * Travis-CI cannot handle these tests because PHPUnit output is echoed preventing
 * the session from starting.
 */

class Elgg_Http_NativeSessionStorageTest extends PHPUnit_Framework_TestCase {

	/** @var Elgg_Http_NativeSessionStorage */
	protected $storage;

	protected function setUp() {
		$this->storage = new Elgg_Http_NativeSessionStorage(array(), new Elgg_Http_MockSessionHandler());
	}

	protected function tearDown() {
		if ($this->storage->isStarted()) {
			session_destroy();
		}
	}

	public function testGetId() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->assertEquals('', $this->storage->getId());
		$this->storage->start();
		$this->assertNotEquals('', $this->storage->getId());
	}

	public function testSetId() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->setId('sessionid');
		$this->storage->start();
		$this->assertEquals('sessionid', $this->storage->getId());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testSetIdAfterStart() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->storage->setId('sessionid');
		$this->assertNotEquals('sessionid', $this->storage->getId());
	}

	public function testGetIdBeforeStart() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->assertEquals('', $this->storage->getId());
	}

	public function testSetNameBeforeStart() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->setName('foo');
		$this->storage->start();
		$this->assertEquals('foo', $this->storage->getName());
	}

	public function testSetNameAfterStart() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->storage->setName('foo');
		$this->assertEquals('foo', $this->storage->getName());
	}

	public function testRegenerate() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$id = $this->storage->getId();
		$this->storage->set('lucky', 7);
		$this->storage->regenerate();
		$this->assertNotEquals($id, $this->storage->getId());
		$this->assertEquals(7, $this->storage->get('lucky'));
	}

	public function testRegenerateDestroy() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$id = $this->storage->getId();
		$this->storage->set('legs', 11);
		$this->storage->regenerate(true);
		$this->assertNotEquals($id, $this->storage->getId());
		$this->assertEquals(11, $this->storage->get('legs'));
	}


	protected function setUpAttributes() {
		$this->data = array(
			'user' => new stdClass(),
			'guid' => 64,
			'msg' => array('success' => 'You are logged in'),
		);
		$this->storage->replace($this->data);
	}

	public static function provideAttributes() {
		return array(
			array('user', new stdClass(), true),
			array('guid', 64, true),
			array('msg', array('success' => 'You are logged in'), true),
			array('not_exist', null, false),
		);
	}

	/**
	 * @dataProvider provideAttributes
	 */
	public function testHas($key, $value, $exists) {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->setUpAttributes();
		$this->assertEquals($exists, $this->storage->has($key));
	}

	/**
	 * @dataProvider provideAttributes
	 */
	public function testGet($key, $value, $exists) {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->setUpAttributes();
		$this->assertEquals($value, $this->storage->get($key));
	}

	public function testGetDefault() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->assertEquals('test', $this->storage->get('foo', 'test'));
	}

	/**
	 * @dataProvider provideAttributes
	 */
	public function testSet($key, $value, $expected) {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->storage->set($key, $value);
		$this->assertEquals($value, $this->storage->get($key));
	}

	public function testAll() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->setUpAttributes();
		$this->assertEquals($this->data, $this->storage->all());
	}

	public function testReplace() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->setUpAttributes();
		$this->assertEquals(64, $this->storage->get('guid'));
		$array = array();
		$array['one'] = 1;
		$array['two'] = 2;
		$this->storage->replace($array);
		$this->assertEquals($array, $this->storage->all());
		$this->assertNull($this->storage->get('guid'));
	}

	public function testRemove() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->setUpAttributes();
		$this->assertEquals(64, $this->storage->remove('guid'));
		$this->assertNull($this->storage->get('guid'));
	}

	public function testClear() {
		if (headers_sent()) {
			$this->markTestSkipped("Cannot run session tests");
		}
		$this->storage->start();
		$this->storage->clear();
		$this->assertEquals(array(), $this->storage->all());
	}

}
