<?php

namespace Elgg\Discussions;

use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

class ElggDiscussionUnitTest extends UnitTestCase {
	
	protected ?\ElggDiscussion $entity = null;
	
	public function up() {
		$this->startPlugin();
		
		parent::up();
		
		$this->entity = $this->createObject([
			'subtype' => 'discussion',
		]);
	}
	
	public function testDefaultStatus() {
		$entity = new \ElggDiscussion();
		
		$this->assertEquals('open', $entity->status);
	}
	
	public function testNoMagicSetterForStatus() {
		$this->expectException(InvalidArgumentException::class);
		$this->entity->status = 'foo';
	}
	
	public function testSetStatusWithInvalidStatus() {
		$this->expectException(DomainException::class);
		$this->entity->setStatus('foo');
	}
	
	/**
	 * @dataProvider validStatusProvider
	 */
	public function testSetStatusWithValidStatus(string $new_status) {
		$this->entity->setStatus($new_status);
		$this->assertEquals($new_status, $this->entity->status);
		
		// setting to the same value should not give any errors
		$this->entity->setStatus($new_status);
		$this->assertEquals($new_status, $this->entity->status);
	}
	
	public static function validStatusProvider(): array {
		return [
			['open'],
			['closed'],
		];
	}
}
