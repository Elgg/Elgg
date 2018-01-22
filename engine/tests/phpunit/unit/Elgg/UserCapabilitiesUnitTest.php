<?php

namespace Elgg;

use ElggAnnotation;
use ElggEntity;
use ElggMetadata;
use ElggObject;
use ElggUser;
use InvalidArgumentException;

/**
 * @group UnitTests
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

	/**
	 * @group UserCapabilities
	 */
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

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canEdit($viewer->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

	/**
	 * @group UserCapabilities
	 */
	public function testCanUserEditSelf() {
		$user = $this->createUser();
		$viewer = $this->createUser();
		$this->assertTrue($user->canEdit($user->guid));
		$this->assertFalse($user->canEdit($viewer->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($user->canEdit($admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($user->canEdit($viewer->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideEditPermissionsWithAHook() {

		$user = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $user->guid,
		]);

		$this->assertTrue($entity->canEdit($user->guid));

		$this->hooks->registerHandler('permissions_check', 'object', function($hook, $type, $return, $params) use ($entity, $user) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($user, $params['user']);
			$this->assertTrue($return);
			return false;
		});
		$this->assertFalse($entity->canEdit($user->guid));

		// Permissions hooks should not be triggered for admin users and with ignored access
		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEdit($admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canEdit($user->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideDeletePermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canDelete($owner->guid));

		$this->hooks->registerHandler('permissions_check:delete', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canDelete($owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEdit($admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canEdit($owner->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanWriteToContainerWhenCanEdit() {
		$owner = $this->createUser();
		$container = $this->createUser();
		$viewer = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'container_guid' => $container->guid,
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid));
		$this->assertTrue($entity->canWriteToContainer($container->guid));
		$this->assertEquals($entity->canEdit($owner->guid), $entity->canDelete($owner->guid));
		$this->assertEquals($entity->canEdit($container->guid), $entity->canDelete($container->guid));

		$this->assertFalse($entity->canWriteToContainer($viewer->guid));
		$this->assertEquals($entity->canEdit($viewer->guid), $entity->canDelete($viewer->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideContainerPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
			'subtype' => 'bar',
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		$this->hooks->registerHandler('container_permissions_check', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['container']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['container']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('bar', $params['subtype']);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// Should still be able to write to container without particular entity type specified
		$this->assertTrue($entity->canWriteToContainer($owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canWriteToContainer($admin_user->guid, 'object', 'bar'));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanNotEditMetadataOfUnsavedEntity() {
		$user = $this->createUser();
		$entity = new ElggObject();
		$this->assertFalse($entity->canEditMetadata(null, $user->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testOwnerCanEditUnownedMetadata() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$metadata = new ElggMetadata((object) [
			'owner_guid' => 0,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($entity->canEditMetadata($metadata, $owner->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testOwnerCanEditMetadataOwnedBySelf() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$metadata = new ElggMetadata((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($entity->canEditMetadata($metadata, $owner->guid));

		$viewer = $this->createUser();
		$this->assertTrue($entity->canEditMetadata($metadata, $viewer->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanEditMetadataWhenCanEdit() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canEdit($owner->guid));
		$this->assertTrue($entity->canEditMetadata(null, $owner->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideMetadataPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$metadata = new ElggMetadata((object) [
			'owner_guid' => $owner->guid,
			'entity_guid' => $entity->guid,
		]);
		$this->assertTrue($entity->canEditMetadata($metadata, $owner->guid));

		$this->hooks->registerHandler('permissions_check:metadata', 'object', function($hook, $type, $return, $params) use ($entity, $owner, $metadata) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertInstanceOf(ElggMetadata::class, $params['metadata']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals($metadata, $params['metadata']);
			$this->assertEquals('object', $type);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canEditMetadata($metadata, $owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canEditMetadata($metadata, $admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canEditMetadata($metadata, $owner->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

	/**
	 * @group UserCapabilities
	 */
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

		$ia = elgg_set_ignore_access();
		$this->assertTrue($annotation->canEdit($viewer->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

		$ia = elgg_set_ignore_access();
		$this->assertTrue($annotation->canEdit($viewer->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

	/**
	 * @group UserCapabilities
	 */
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

		$this->hooks->registerHandler('permissions_check', 'annotation', function($hook, $type, $return, $params) use ($entity, $owner, $annotation) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertInstanceOf(ElggAnnotation::class, $params['annotation']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals($annotation, $params['annotation']);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($annotation->canEdit($owner->guid));


		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($annotation->canEdit($admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($annotation->canEdit($owner->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
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

		$ia = elgg_set_ignore_access();
		$this->assertTrue($object->canComment($viewer->guid));
		elgg_set_ignore_access($ia);

		// can't comment on comment
		$comment = new \ElggComment();
		$comment->owner_guid = $owner->guid;
		$this->assertFalse($comment->canComment($owner->guid));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideCommentingPermissionsWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		
		$this->assertTrue($entity->canComment($owner->guid));

		$this->hooks->registerHandler('permissions_check:comment', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('object', $type);
			$this->assertNull($return); // called from ElggObject, no default value
			return false;
		});

		$this->assertFalse($entity->canComment($owner->guid));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canComment($admin_user->guid));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canComment($owner->guid));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 * @expectedException InvalidArgumentException
	 */
	public function testCanAnnotateThrowsExceptionForNameArgumentSetToAnnotationInstance() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$entity->canAnnotate($owner->guid, new ElggAnnotation());
	}

	/**
	 * @group UserCapabilities
	 * @expectedException InvalidArgumentException
	 */
	public function testCanAnnotateThrowsExceptionForNameArgumentSetToArray() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$entity->canAnnotate($owner->guid, []);
	}

	/**
	 * @group UserCapabilities
	 * @expectedException InvalidArgumentException
	 */
	public function testCanAnnotateThrowsExceptionForNameArgumentSetToInteger() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$entity->canAnnotate($owner->guid, 5);
	}

	/**
	 * @group UserCapabilities
	 * @expectedException InvalidArgumentException
	 */
	public function testCanAnnotateThrowsExceptionForNameArgumentSetToClosure() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$entity->canAnnotate($owner->guid, function() {
			return 'annotation_name';
		});
	}

	/**
	 * @group UserCapabilities
	 */
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

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideAnnotationPermissionsWithAHookByAnnotationName() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate:baz', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('baz', $params['annotation_name']);
			$this->assertEquals('object', $type);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canAnnotate($admin_user->guid, 'baz'));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideAnnotationPermissionsWithAGenericHook() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('baz', $params['annotation_name']);
			$this->assertEquals('object', $type);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$admin_user = $this->createUser([], [
			'admin' => 'yes',
		]);
		$this->assertTrue($entity->canAnnotate($admin_user->guid, 'baz'));

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanAnnotateHookSequence() {
		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate:baz', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('baz', $params['annotation_name']);
			$this->assertEquals('object', $type);
			$this->assertTrue($return);
			return false;
		});

		$this->assertFalse($entity->canAnnotate($owner->guid, 'baz'));

		$this->hooks->registerHandler('permissions_check:annotate', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['entity']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['entity']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('baz', $params['annotation_name']);
			$this->assertEquals('object', $type);
			$this->assertFalse($return); // return from named hook
			return true;
		});

		$this->assertTrue($entity->canAnnotate($owner->guid, 'baz'));
	}

	/**
	 * @group UserCapabilities
	 */
	public function testCanOverrideContainerLogicWithAHook() {

		$owner = $this->createUser();
		$entity = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$this->assertTrue($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		$this->hooks->registerHandler('container_logic_check', 'object', function($hook, $type, $return, $params) use ($entity, $owner) {
			$this->assertInstanceOf(ElggEntity::class, $params['container']);
			$this->assertInstanceOf(ElggUser::class, $params['user']);
			$this->assertEquals($entity, $params['container']);
			$this->assertEquals($owner, $params['user']);
			$this->assertEquals('object', $type);
			$this->assertEquals('bar', $params['subtype']);
			$this->assertNull($return);
			return false;
		});

		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));

		// make sure container permission hooks are not triggered
		$this->hooks->registerHandler('container_permissions_check', 'object', function() {
			return true;
		});
	}

	/**
	 * @group UserCapabilities
	 */
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

		$ia = elgg_set_ignore_access();
		$this->assertFalse($entity->canWriteToContainer($owner->guid, 'object', 'bar'));
		elgg_set_ignore_access($ia);
	}

	/**
	 * @group UserCapabilities
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
	 * @group UserCapabilities
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

		$ia = elgg_set_ignore_access();
		$this->assertTrue($entity->canDownload($admin_user->guid));
		elgg_set_ignore_access($ia);
	}
}
