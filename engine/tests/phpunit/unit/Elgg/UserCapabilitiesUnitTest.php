<?php

namespace Elgg;

use ElggAnnotation;
use ElggEntity;
use ElggUser;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * @group UnitTests
 * @group UserCapabilities
 */
class UserCapabilitiesUnitTest extends UnitTestCase {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	public function up() {
		$this->hooks = _elgg_services()->hooks;
		$this->hooks->backup();

		// make sure ignored access doesn't overflow from previous test
		$this->assertFalse(elgg_get_ignore_access());
	}

	public function down() {
		$this->hooks->restore();

		// make sure methods called during the test do not leave behind ignored access
		$this->assertFalse(elgg_get_ignore_access());
	}

	public function testOwnerCanEditEntity() {
		$owner = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canEdit($owner->guid));
		$this->assertFalse($entity->canEdit($viewer->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $viewer) {
			$this->assertTrue($entity->canEdit($viewer->guid));
		});
	}

	public function testContainerCanEditEntity() {
		$owner = $this->createUser();
		$container = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'container_guid' => $container->guid,
		]);
		$this->assertFalse($entity->canEdit($viewer->guid));
		$this->assertTrue($entity->canEdit($owner->guid));
		$this->assertTrue($entity->canEdit($container->guid));
	}

	public function testCanUserEditSelf() {
		$user = $this->createUser();
		$viewer = $this->createUser();
		$this->assertTrue($user->canEdit($user->guid));
		$this->assertFalse($user->canEdit($viewer->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($user->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($user, $viewer) {
			$this->assertTrue($user->canEdit($viewer->guid));
		});
	}

	public function testCanOverrideEditPermissionsWithAHook() {

		$user = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertTrue($entity->canEdit($user->guid));

		$this->hooks->registerHandler('permissions_check', 'object', function(\Elgg\Hook $hook) use ($entity, $user) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($user, $hook->getUserParam());
			$this->assertTrue($hook->getValue());
			return false;
		});
		$this->assertFalse($entity->canEdit($user->guid));

		// Permissions hooks should not be triggered for admin users and with ignored access
		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $user) {
			$this->assertTrue($entity->canEdit($user->guid));
		});
	}

	public function testCanDeleteWhenCanEdit() {
		$owner = $this->createUser();
		$container = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'container_guid' => $container->guid,
		]);

		$this->assertTrue($entity->canDelete($owner->guid));
		$this->assertTrue($entity->canDelete($container->guid));
		$this->assertEquals($entity->canEdit($owner->guid), $entity->canDelete($owner->guid));
		$this->assertEquals($entity->canEdit($container->guid), $entity->canDelete($container->guid));

		$this->assertFalse($entity->canDelete($viewer->guid));
		$this->assertEquals($entity->canEdit($viewer->guid), $entity->canDelete($viewer->guid));
	}

	public function testCanOverrideDeletePermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canDelete($owner->guid));

		$this->hooks->registerHandler('permissions_check:delete', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canDelete($owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertTrue($entity->canEdit($owner->guid));
		});
	}

	public function testCanWriteToContainerWhenCanEdit() {
		$owner = $this->createUser();
		$container = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'container_guid' => $container->guid,
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'foo'));
		$this->assertTrue($entity->canWriteToContainer($container->guid, 'object', 'foo'));
		$this->assertEquals($entity->canEdit($owner->guid), $entity->canDelete($owner->guid));
		$this->assertEquals($entity->canEdit($container->guid), $entity->canDelete($container->guid));

		$this->assertFalse($entity->canWriteToContainer($viewer->guid, 'object', 'foo'));
		$this->assertEquals($entity->canEdit($viewer->guid), $entity->canDelete($viewer->guid));
	}

	public function testCanOverrideContainerPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'subtype' => 'bar',
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// only prevent the write access for 'object', 'bar'
		$this->hooks->registerHandler('container_permissions_check', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertNotEmpty($hook->getParam('subtype'));
			if ($hook->getParam('subtype') !== 'bar') {
				return;
			}
			
			$this->assertInstanceOf(ElggEntity::class, $hook->getParam('container'));
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getParam('container'));
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// Should still be able to write to container with a different type/subtype
		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'foo'));

		// Admins should always be allowed
		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canWriteToContainer($admin_user->guid, 'object', 'bar'));

		// when access is ignored it should also be allowed
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));
		});
	}

	public function testOwnerCanEditUnownedAnnotationOnOwnedEntity() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$annotation = new ElggAnnotation((object) [
			'owner_guid' => 0,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($annotation->canEdit($owner->guid));
	}

	public function testOwnerCanEditAnnotationOwnedBySelfOnOwnedEntity() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$annotation = new ElggAnnotation((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($annotation->canEdit($owner->guid));

		$viewer = $this->createUser();
		$this->assertFalse($annotation->canEdit($viewer->guid));


		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($annotation->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($annotation, $viewer) {
			$this->assertTrue($annotation->canEdit($viewer->guid));
		});
	}

	public function testOwnerCanEditAnnotationOwnedBySelfOnUnownedEntity() {
		$owner = $this->createUser();
		$entity = $this->createObject();

		$annotation = new ElggAnnotation((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);

		$this->assertTrue($annotation->canEdit($owner->guid));

		$viewer = $this->createUser();
		$this->assertFalse($annotation->canEdit($viewer->guid));


		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($annotation->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($annotation, $viewer) {
			$this->assertTrue($annotation->canEdit($viewer->guid));
		});
	}

	public function testCanEditAnnotationWhenCanEdit() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$annotation = new ElggAnnotation((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($entity->canEdit($owner->guid));
		$this->assertTrue($annotation->canEdit($owner->guid));
	}

	public function testCanOverrideAnnotationPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$annotation = new ElggAnnotation((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($annotation->canEdit($owner->guid));

		$this->hooks->registerHandler('permissions_check', 'annotation', function(\Elgg\Hook $hook) use ($entity, $owner, $annotation) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertInstanceOf(ElggAnnotation::class, $hook->getParam('annotation'));
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals($annotation, $hook->getParam('annotation'));
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($annotation->canEdit($owner->guid));


		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($annotation->canEdit($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($annotation, $owner) {
			$this->assertTrue($annotation->canEdit($owner->guid));
		});
	}

	public function testDefaultCanCommentPermissions() {

		$viewer = $this->createUser();

		$owner = $this->createUser();
		$group = $this->createGroup([
			'owner_guid', $owner->guid,
		]);

		$object = $this->createObject([
			'owner_guid', $owner->guid,
		]);

		$entity = $this->getMockBuilder(ElggEntity::class)
			->setMethods(['__get', 'getDisplayName', 'setDisplayName']) // keep origin canComment method
			->disableOriginalConstructor()
			->getMock();

		$entity->expects($this->any())
			->method('__get')
			->will($this->returnValueMap([
				['owner_guid', $owner->guid]
			]));

		$this->assertFalse($owner->canComment($owner->guid));
		$this->assertTrue($object->canComment($owner->guid));
		$this->assertFalse($group->canComment($owner->guid));
		$this->assertNull($entity->canComment($owner->guid));

		$this->assertFalse($owner->canComment($viewer->guid));
		$this->assertTrue($object->canComment($viewer->guid));
		$this->assertFalse($group->canComment($viewer->guid));
		$this->assertNull($entity->canComment($viewer->guid));

		// can pass default value
		$this->assertTrue($object->canComment($viewer->guid, true));
		$this->assertFalse($object->canComment($viewer->guid, false));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($object->canComment($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object, $viewer) {
			$this->assertTrue($object->canComment($viewer->guid));
		});

		// can't comment on comment
		$comment = new \ElggComment();
		$comment->owner_guid = $owner->guid;
		$this->assertFalse($comment->canComment($owner->guid));
	}

	public function testCanOverrideCommentingPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		
		$this->assertTrue($entity->canComment($owner->guid));

		$this->hooks->registerHandler('permissions_check:comment', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('object', $hook->getType());
			$this->assertNull($hook->getValue()); // called from ElggObject, no default value
			return false;
		});

		$this->assertFalse($entity->canComment($owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canComment($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertTrue($entity->canComment($owner->guid));
		});
	}

	public function testCanAnnotateThrowsExceptionForNameArgumentSetToAnnotationInstance() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->expectException(InvalidArgumentException::class);
		$entity->canAnnotate($owner->guid, new ElggAnnotation());
	}

	public function testCanAnnotateThrowsExceptionForNameArgumentSetToArray() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->expectException(InvalidArgumentException::class);
		$entity->canAnnotate($owner->guid, []);
	}

	public function testCanAnnotateThrowsExceptionForNameArgumentSetToInteger() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->expectException(InvalidArgumentException::class);
		$entity->canAnnotate($owner->guid, 5);
	}

	public function testCanAnnotateThrowsExceptionForNameArgumentSetToClosure() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->expectException(InvalidArgumentException::class);
		$entity->canAnnotate($owner->guid, function() {
			return 'annotation_name';
		});
	}

	public function testCanAnnotateByDefault() {

		$viewer = $this->createUser();
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		// Entity owner can annotate
		$this->assertTrue($entity->canAnnotate($owner->guid));
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
		$this->assertTrue($entity->canAnnotate($owner->guid, ''));
		$this->assertTrue($entity->canAnnotate($owner->guid, false)); //BC
		$this->assertTrue($entity->canAnnotate($owner->guid, null)); //BC
		// All other users can annotate
		$this->assertTrue($entity->canAnnotate($viewer->guid));
		$this->assertTrue($viewer->canAnnotate($viewer->guid, 'baz'));
		$this->assertTrue($viewer->canAnnotate($viewer->guid, ''));
		$this->assertTrue($viewer->canAnnotate($viewer->guid, false)); //BC
		$this->assertTrue($viewer->canAnnotate($viewer->guid, null)); //BC
	}

	public function testCanOverrideAnnotationPermissionsWithAHookByAnnotationName() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate:baz', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('baz', $hook->getParam('annotation_name'));
			$this->assertEquals('object', $hook->getType());
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canAnnotate($admin_user->guid, 'baz'));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
		});
	}

	public function testCanOverrideAnnotationPermissionsWithAGenericHook() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('baz', $hook->getParam('annotation_name'));
			$this->assertEquals('object', $hook->getType());
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canAnnotate($admin_user->guid, 'baz'));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
		});
	}

	public function testCanAnnotateHookSequence() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate:baz', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('baz', $hook->getParam('annotation_name'));
			$this->assertEquals('object', $hook->getType());
			$this->assertTrue($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getEntityParam());
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getEntityParam());
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('baz', $hook->getParam('annotation_name'));
			$this->assertEquals('object', $hook->getType());// return from named hook
			return true;
		});

		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
	}

	public function testCanOverrideContainerLogicWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		$this->hooks->registerHandler('container_logic_check', 'object', function(\Elgg\Hook $hook) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $hook->getParam('container'));
			$this->assertInstanceOf(ElggUser::class, $hook->getUserParam());
			$this->assertEquals($entity, $hook->getParam('container'));
			$this->assertEquals($owner, $hook->getUserParam());
			$this->assertEquals('object', $hook->getType());
			$this->assertEquals('bar', $hook->getParam('subtype'));
			$this->assertNull($hook->getValue());
			return false;
		});

		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// make sure container permission hooks are not triggered
		$this->hooks->registerHandler('container_permissions_check', 'object', function() {
			return true;
		});
	}

	public function testContainerLogicOverridesAreRespectedWhenAccessIsIgnored() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		$this->hooks->registerHandler('container_logic_check', 'object', [Values::class, 'getFalse']);

		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// Container logic checks should not be affected admin permissions or ignored access
		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertFalse($entity->canWriteToContainer($admin_user->guid, 'object', 'bar'));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $owner) {
			$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));
		});
	}

	/**
	 * @group FileService
	 */
	public function testCanDownloadFileByDefault() {

		elgg_set_entity_class('object', 'file', \ElggFile::class);

		$owner = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'subtype' => 'file',
			'owner_guid' => $owner->guid,
		]);

		$this->assertTrue($entity->canDownload());
		$this->assertTrue($entity->canDownload($owner->guid));
		$this->assertTrue($entity->canDownload($viewer->guid));
	}

	/**
	 * @group FileService
	 */
	public function testCanAlterDownloadPermissions() {

		elgg_set_entity_class('object', 'file', \ElggFile::class);

		$owner = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'subtype' => 'file',
			'owner_guid' => $owner->guid,
		]);

		$this->hooks->registerHandler('permissions_check:download', 'file', [Values::class, 'getFalse']);

		$this->assertFalse($entity->canDownload());
		$this->assertFalse($entity->canDownload($owner->guid));
		$this->assertFalse($entity->canDownload($viewer->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canDownload($admin_user->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $admin_user) {
			$this->assertTrue($entity->canDownload($admin_user->guid));
		});
	}
}
