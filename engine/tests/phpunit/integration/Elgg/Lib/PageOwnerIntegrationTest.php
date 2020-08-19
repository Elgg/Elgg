<?php

namespace Elgg\Lib;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\IntegrationTestCase;
use Elgg\Router\Route;

class PageOwnerIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		
	}
	
	public function down() {
		
	}

	/**
	 * @dataProvider setterProvider
	 */
	public function testSetPageOwner($initial_guid, $new_guid, $expected) {
		
		_elgg_services()->pageOwner->setPageOwnerGuid($initial_guid);
		
		_elgg_services()->pageOwner->setPageOwnerGuid($new_guid);
		
		$this->assertEquals($expected, _elgg_services()->pageOwner->getPageOwnerGuid());
	}
	
	public function setterProvider() {
		return [
			[999, 1, 1],
			[999, 0, 0],
		];
	}

	/**
	 * @dataProvider libSetSetterProvider
	 */
	public function testSetPageOwnerWithSetterLibFunction($initial_guid, $new_guid, $expected) {
		
		elgg_set_page_owner_guid($initial_guid);
		$this->assertEquals($initial_guid, _elgg_services()->pageOwner->getPageOwnerGuid());
		
		elgg_set_page_owner_guid($new_guid);
		
		$this->assertEquals($expected, _elgg_services()->pageOwner->getPageOwnerGuid());
	}
	
	public function libSetSetterProvider() {
		return [
			[999, 1, 1],
			[999, 0, 0], // different behaviour in getter function
			[999, -1, 0],
			[999, false, 0],
			[999, null, 0],
		];
	}
		
	public function testSettingNegativeOwner() {
		$this->expectException(InvalidArgumentException::class);
		_elgg_services()->pageOwner->setPageOwnerGuid(-1);
	}
	
	/**
	 * @dataProvider routeProvider
	 */
	public function testPageOwnerDetectedFromRoute($url_part, $match_on) {
		self::createApplication(['isolate'=> true]);
		
		$user = $this->createUser(['container_guid' => 1]);
		
		$route = new Route("/foo/{$url_part}");
		$route->setMatchedParameters([
			'_route' => "{$url_part}:foo:bar",
			$match_on => $user->{$match_on},
		]);
		_elgg_services()->request->setRoute($route);
		
		$guid = _elgg_services()->pageOwner->getPageOwnerGuid();
		if ($match_on === 'container_guid') {
			$this->assertEquals($user->container_guid, $guid);
		} else {
			$this->assertEquals($user->guid, $guid);
		}
		
		$user->delete();
	}

	public function routeProvider() {
		return [
			['view', 'username'],
			['view', 'guid'],
			['edit', 'username'],
			['edit', 'guid'],
			['add', 'username'],
			['add', 'guid'],
			['add', 'container_guid'],
			['collection', 'username'],
			['collection', 'guid'],
			['collection', 'container_guid'],
		];
	}
	
	public function testPageOwnerDetectedFromInputUsername() {
		self::createApplication(['isolate'=> true]);
		
		$user = $this->createUser();
		
		_elgg_services()->request->setParam('username', $user->username);
		
		$guid = _elgg_services()->pageOwner->getPageOwnerGuid();
		$this->assertEquals($user->guid, $guid);
		
		$user->delete();
	}
	
	public function testPageOwnerDetectedFromInputOwnerGUID() {
		self::createApplication(['isolate'=> true]);
		
		$user = $this->createUser();
		
		_elgg_services()->request->setParam('owner_guid', $user->guid);
		
		$guid = _elgg_services()->pageOwner->getPageOwnerGuid();
		$this->assertEquals($user->guid, $guid);
		
		$user->delete();
	}
	
	/**
	 * @dataProvider segmentsProvider
	 */
	public function testPageOwnerDetectedFromURLSegments($segment1, $match_on, $test_match_on) {
		$user = $this->createUser(['container_guid' => 1]);
		
		self::createApplication([
			'isolate'=> true,
			'request' => $this->prepareHttpRequest("foo/{$segment1}/{$user->$match_on}"),
		]);
				
		$guid = _elgg_services()->pageOwner->getPageOwnerGuid();
		$this->assertEquals($user->{$test_match_on}, $guid);
		
		$user->delete();
	}
	
	public function segmentsProvider() {
		return [
			['owner', 'username', 'guid'],
			['friends', 'username', 'guid'],
			['view', 'guid', 'container_guid'],
			['edit', 'guid', 'container_guid'],
			['add', 'guid', 'guid'],
			['group', 'guid', 'guid'],
		];
	}
}
