<?php

namespace Elgg;

/**
 * @group Gatekeeper
 */
class GatekeeperTest extends UnitTestCase {

	/**
	 * @var Gatekeeper
	 */
	protected $gatekeeper;

	/**
	 * @var \ElggSession
	 */
	protected $session;

	public function up() {
		$this->session = _elgg_services()->session;
		$this->gatekeeper = _elgg_services()->gatekeeper;
	}

	public function down() {
		$this->session->removeLoggedInUser();
	}

	/**
	 * @expectedException \Elgg\Http\Exception\LoggedInGatekeeperException
	 */
	public function testGatekeeperPreventsAccessByGuestUser() {
		$this->gatekeeper->assertAuthenticatedUser();
	}

	public function testGatekeeperAllowsAccessToLoggedInUser() {

		$user = $this->createUser();
		$this->session->setLoggedInUser($user);

		$this->assertNull($this->gatekeeper->assertAuthenticatedUser());
	}

	/**
	 * @expectedException \Elgg\Http\Exception\LoggedOutGatekeeperException
	 */
	public function testGatekeeperPreventsAccessByLoggedInUser() {
		$user = $this->createUser();
		$this->session->setLoggedInUser($user);
		
		$this->gatekeeper->assertUnauthenticatedUser();
		
	}

	public function testGatekeeperAllowsAccessToGuestUser() {
		$this->assertNull($this->gatekeeper->assertUnauthenticatedUser());
	}

	/**
	 * @expectedException \Elgg\GatekeeperException
	 */
	public function testAdminGatekeeperPreventsAccessByGuestUser() {
		$this->gatekeeper->assertAuthenticatedAdmin();
	}

	/**
	 * @expectedException \Elgg\Http\Exception\AdminGatekeeperException
	 */
	public function testAdminGatekeeperPreventsAccessByNonAdminUser() {
		$user = $this->createUser();
		$this->session->setLoggedInUser($user);

		$this->gatekeeper->assertAuthenticatedAdmin();
	}

	public function testAdminGatekeeperAllowsAccessToLoggedInAdmin() {

		$user = $this->createUser([
			'admin' => 'yes',
		]);
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

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 */
	public function testEntityGatekeeperPreventsAccessToNonPublicEntity() {
		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
			'owner_guid' => $user->guid,
		]);

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

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testEntityGatekeeperPreventsAccessByType() {
		$user = $this->createUser();

		$object = $this->createObject([
			'type' => 'object',
			'subtype' => 'foo1',
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		elgg_entity_gatekeeper($object->guid, 'object', 'foo2');
	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testEntityGatekeeperPreventsAccessBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);

		$this->gatekeeper->assertAccessibleEntity($user);

	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testEntityGatekeeperPreventsAccessToContentOwnedByBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
		]);

		$this->gatekeeper->assertAccessibleEntity($object);

	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testEntityGatekeeperPreventsAccessToContainedByBannedUser() {
		$user = $this->createUser([
			'banned' => 'yes'
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $user->guid,
		]);

		$this->gatekeeper->assertAccessibleEntity($object);

	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testEntityGatekeeperPreventsAccessToDisabledEntity() {

		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
			'enabled' => 'no',
		]);

		$this->gatekeeper->assertAccessibleEntity($object);

	}

	/**
	 * @expectedException \Elgg\EntityPermissionsException
	 */
	public function testEntityGatekeeperPreventsAccessToPublicEntityWithNonPublicParent() {

		$container = $this->createObject([
			'access_id' => ACCESS_LOGGED_IN,
		]);

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'container_guid' => $container->guid,
		]);

		$this->gatekeeper->assertAccessibleEntity($object);

	}

	public function testEntityGatekeeperAllowsAccessToDisabledEntityWithShownHiddenEntities() {

		$user = $this->createUser();

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
			'owner_guid' => $user->guid,
			'enabled' => 'no',
		]);

		access_show_hidden_entities(true);
		$this->assertNull($this->gatekeeper->assertAccessibleEntity($object));
		access_show_hidden_entities(false);

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

	/**
	 * @expectedException \Elgg\GroupGatekeeperException
	 */
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

		$this->gatekeeper->assertAccessibleEntity($object);

	}

	/**
	 * @expectedException \Elgg\GroupGatekeeperException
	 */
	public function testEntityGatekeeperPreventsAccessToAGroupWithRestrictedContentPolicy() {

		$group = $this->createGroup([
			'membership' => ACCESS_PUBLIC,
			'access_id' => ACCESS_PUBLIC,
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		]);

		$viewer = $this->createUser();
		$this->session->setLoggedInUser($viewer);

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

	/**
	 * @expectedException \Elgg\HttpException
	 */
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

		if ($ex instanceof \Exception) {
			throw $ex;
		}

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

	/**
	 * @expectedException \Elgg\Http\Exception\AjaxGatekeeperException
	 */
	public function testXhrGatekeeperPreventsAccess() {
		$this->gatekeeper->assertXmlHttpRequest();
	}
}
