<?php

namespace Elgg;

use Elgg\Exceptions\HttpException;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\AdminGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\AjaxGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\GroupGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\LoggedInGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\LoggedOutGatekeeperException;

/**
 * @group Gatekeeper
 */
class GatekeeperUnitTest extends UnitTestCase {

	/**
	 * @var Gatekeeper
	 */
	protected $gatekeeper;

	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * @var Invoker
	 */
	protected $invoker;

	public function up() {
		$this->session = _elgg_services()->session;
		$this->gatekeeper = _elgg_services()->gatekeeper;
		$this->invoker = _elgg_services()->invoker;
	}

	public function down() {
		$this->session->removeLoggedInUser();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function createUser(array $attributes = [], array $metadata = []) {
		return $this->invoker->call(ELGG_IGNORE_ACCESS, function() use ($attributes, $metadata) {
			return parent::createUser($attributes, $metadata);
		});
	}

	public function testGatekeeperPreventsAccessByGuestUser() {
		$this->expectException(LoggedInGatekeeperException::class);
		$this->gatekeeper->assertAuthenticatedUser();
	}

	public function testGatekeeperAllowsAccessToLoggedInUser() {

		$user = $this->createUser();
		$this->session->setLoggedInUser($user);

		$this->assertNull($this->gatekeeper->assertAuthenticatedUser());
	}

	public function testGatekeeperPreventsAccessByLoggedInUser() {
		$user = $this->createUser();
		$this->session->setLoggedInUser($user);

		$this->expectException(LoggedOutGatekeeperException::class);
		$this->gatekeeper->assertUnauthenticatedUser();
	}

	public function testGatekeeperAllowsAccessToGuestUser() {
		$this->assertNull($this->gatekeeper->assertUnauthenticatedUser());
	}
	
	public function testGatekeeperPreventsAccessToBannedUserByGuestUser() {
		$user = $this->createUser([
			'banned' => 'yes',
		]);
		
		$this->assertTrue($user->isBanned());
		
		$this->expectException(EntityNotFoundException::class);
		$this->gatekeeper->assertAccessibleUser($user);
	}
	
	public function testGatekeeperPreventsAccessToBannedUserByLoggedInUser() {
		$user = $this->createUser([
			'banned' => 'yes',
		]);
		$this->assertTrue($user->isBanned());
		
		$authenticated_user = $this->createUser();
		$this->session->setLoggedInUser($authenticated_user);
		
		$this->expectException(EntityNotFoundException::class);
		$this->gatekeeper->assertAccessibleUser($user);
	}
	
	public function testGatekeeperAllowsAccessToNonBannedUser() {
		$user = $this->createUser([
			'banned' => 'no',
		]);
		$this->assertFalse($user->isBanned());
		
		$this->gatekeeper->assertAccessibleUser($user);
	}
	
	public function testGatekeeperAllowsAccessToBannedUserByAdmin() {
		$user = $this->createUser([
			'banned' => 'yes',
		]);
		$this->assertTrue($user->isBanned());
		
		$admin = $this->createUser([
			'admin' => 'yes'
		]);
		$this->assertTrue($admin->isAdmin());
		
		$this->session->setLoggedInUser($admin);
		
		$this->gatekeeper->assertAccessibleUser($user);
	}

	public function testAdminGatekeeperPreventsAccessByGuestUser() {
		$this->expectException(GatekeeperException::class);
		$this->gatekeeper->assertAuthenticatedAdmin();
	}

	public function testAdminGatekeeperPreventsAccessByNonAdminUser() {
		$user = $this->createUser();
		$this->session->setLoggedInUser($user);

		$this->expectException(AdminGatekeeperException::class);
		$this->gatekeeper->assertAuthenticatedAdmin();
	}

	public function testAdminGatekeeperAllowsAccessToLoggedInAdmin() {
		$user = $this->createUser([
			'admin' => 'yes',
		]);
		$this->assertTrue($user->isAdmin());
		
		$this->session->setLoggedInUser($user);

		$this->assertNull($this->gatekeeper->assertAuthenticatedAdmin());
	}

	public function testEntityGatekeeperAllowsAccessToPublicEntity() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
	}

	public function testEntityGatekeeperPreventsAccessToNonPublicEntity() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);

		$this->expectException(EntityPermissionsException::class);
		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperAllowsAccessToNonPublicEntityWithIgnoredAccess() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);

		$this->session->setIgnoreAccess();
		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
		$this->session->setIgnoreAccess(false);
	}

	public function testEntityGatekeeperAllowsAccessToAccessControlledEntityByAuthenticatedUser() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);

		$viewer = $this->createUser();

		$this->session->setLoggedInUser($viewer);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
	}
	
	public function testEntityGatekeeperCanEditPass() {
		$user = $this->createUser();
		
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);
		
		$this->session->setLoggedInUser($user);
		
		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object, null, true));
	}
	
	public function testEntityGatekeeperCanEditFail() {
		$user = $this->createUser();
		
		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);
		
		$viewer = $this->createUser();
		
		$this->session->setLoggedInUser($viewer);
		
		$this->expectException(EntityPermissionsException::class);
		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object, null, true));
	}

	public function testEntityGatekeeperPreventsAccessByType() {
		$user = $this->createUser();

		$object = $this->createObject([
			'type' => 'object',
			'subtype' => 'foo1',
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$this->expectException(EntityNotFoundException::class);
		elgg_entity_gatekeeper($object->guid, 'object', 'foo2');
	}

	public function testEntityGatekeeperAllowsAccessBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);
		$this->assertTrue($user->isBanned());

		$this->gatekeeper->assertAccessibleEntity($user);
	}

	public function testEntityGatekeeperAllowsAccessToContentOwnedByBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);
		$this->assertTrue($user->isBanned());

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperAllowsAccessToContainedByBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);
		$this->assertTrue($user->isBanned());

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $user->guid,
		]);

		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperPreventsAccessToDisabledEntity() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
			'enabled' => 'no',
		]);

		$this->expectException(EntityNotFoundException::class);
		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperPreventsAccessToPublicEntityWithNonPublicParent() {
		$container = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $container->guid,
		]);

		$this->expectException(EntityPermissionsException::class);
		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperAllowsAccessToDisabledEntityWithShownHiddenEntities() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
			'enabled' => 'no',
		]);

		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($object) {
			$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
		});
	}

	public function testEntityGatekeeperAllowsAccessToPublicGroupContent() {
		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $group->guid,
		]);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
	}

	public function testEntityGatekeeperAllowsAccessToNonPublicGroupContent() {
		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'container_guid' => $group->guid,
		]);

		$viewer = $this->createUser();
		$this->session->setLoggedInUser($viewer);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
	}

	public function testEntityGatekeeperPreventsAccessToPublicGroupContentWithRestrictedContentPolicy() {
		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $group->guid,
		]);

		$viewer = $this->createUser();
		$this->session->setLoggedInUser($viewer);

		$this->expectException(GroupGatekeeperException::class);
		$this->gatekeeper->assertAccessibleEntity($object);
	}

	public function testEntityGatekeeperPreventsAccessToAGroupWithRestrictedContentPolicy() {
		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$viewer = $this->createUser();
		$this->session->setLoggedInUser($viewer);

		$this->expectException(GroupGatekeeperException::class);
		$this->gatekeeper->assertAccessibleEntity($group);
	}

	public function testEntityGatekeeperAllowsAccessToPublicGroupContentWithRestrictedContentPolicyToGroupMembers() {
		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $group->guid,
		]);

		$viewer = $this->createUser();

		$group->join($viewer);

		$this->session->setLoggedInUser($viewer);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
	}

	public function testEntityGatekeeperCanPreventAccessToEntityWithAHook() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$handler = function (Hook $hook) {
			$this->assertTrue($hook->getValue());

			return new HttpException('Override', ELGG_HTTP_I_AM_A_TEAPOT);
		};

		$hook = $this->registerTestingHook('gatekeeper', "object:$object->subtype", $handler);

		try {
			$this->gatekeeper->assertAccessibleEntity($object);

		} catch (HttpException $ex) {
			$this->assertEquals('Override', $ex->getMessage());
			$this->assertEquals(ELGG_HTTP_I_AM_A_TEAPOT, $ex->getCode());
		}

		$this->assertInstanceOf(HttpException::class, $hook->getResult());

		$hook->unregister();
	}

	public function testEntityGatekeeperCanPreventAccessToEntityWithAHookWithFalseReturn() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$handler = function (Hook $hook) {
			$this->assertTrue($hook->getValue());

			return false;
		};

		$hook = $this->registerTestingHook('gatekeeper', "object:$object->subtype", $handler);

		$ex = null;
		try {
			$this->gatekeeper->assertAccessibleEntity($object);
		} catch (HttpException $ex) {

		}

		$this->assertFalse($hook->getResult());

		$hook->unregister();
		
		$this->assertInstanceOf(HttpException::class, $ex);
	}

	public function testEntityGatekeeperCanAllowAccessToNonAccessibleEntityWithAHook() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PRIVATE,
			'owner_guid' => $user->guid,
		]);

		$handler = function (Hook $hook) {
			$this->assertInstanceOf(EntityPermissionsException::class, $hook->getValue());

			return true;
		};

		$hook = $this->registerTestingHook('gatekeeper', "object:$object->subtype", $handler);

		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));

		$this->assertTrue($hook->getResult());

		$hook->unregister();
	}

	public function testXhrGatekeeperPreventsAccess() {
		$this->expectException(AjaxGatekeeperException::class);
		$this->gatekeeper->assertXmlHttpRequest();
	}
}
