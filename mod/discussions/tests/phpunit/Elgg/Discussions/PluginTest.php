<?php

namespace Elgg\Discussions;

use Elgg\Database\EntityTable;
use Elgg\TestCase;
use Elgg\Tests\EntityMocks;
use ElggDiscussionReply;

/**
 * @group Discussions
 */
class PluginTest extends TestCase {

	/**
	 * @var EntityMocks
	 */
	private $mocks;

	/**
	 *
	 * @var type @var \Elgg\PluginHooksService
	 */
	private $hooks;

	public function setUp() {

		$this->mocks = new EntityMocks($this);

		$this->entities = $this->getMockBuilder(EntityTable::class)
				->setMethods(['get', 'exists'])
				->disableOriginalConstructor()
				->getMock();

		$this->entities->expects($this->any())
				->method('get')
				->will($this->returnCallback([$this->mocks, 'get']));

		$this->entities->expects($this->any())
				->method('exists')
				->will($this->returnCallback([$this->mocks, 'exists']));

		_elgg_services()->setValue('entityTable', $this->entities);

		// We don't want translator loading plugin translations
		_elgg_services()->translator->addTranslation('en', ['__test__' => 'Test']);

		_elgg_services()->hooks->backup();
		_elgg_services()->events->backup();

		require_once elgg_get_plugins_path() . 'discussions/start.php';
		_elgg_services()->autoloadManager->addClasses(elgg_get_plugins_path() . 'discussions/classes/');

		discussion_init();
	}

	public function tearDown() {
		_elgg_services()->hooks->restore();
		_elgg_services()->events->restore();
	}

	public function testCanNotReplyToClosedDiscussion() {

		$user = $this->mocks->getUser();
		$open_discussion = $this->mocks->getObject([
			'subtype' => 'discussion',
			'owner_guid' => $user->guid,
		]);
		$closed_discussion = $this->mocks->getObject([
			'subtype' => 'discussion',
			'owner_guid' => $user->guid,
			'status' => 'closed',
		]);

		$this->assertTrue($open_discussion->canEdit($user->guid));
		$this->assertTrue($open_discussion->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));

		$this->assertTrue($closed_discussion->canEdit($user->guid));
		$this->assertFalse($closed_discussion->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));
	}

	public function testCanOnlyWriteRepliesToDiscussionContainer() {

		$user = $this->mocks->getUser();
		$discussion = $this->mocks->getObject([
			'subtype' => 'discussion',
			'owner_guid' => $user->guid,
		]);
		$blog = $this->mocks->getObject([
			'subtype' => 'blog',
			'owner_guid' => $user->guid,
		]);

		$this->assertTrue($discussion->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));
		$this->assertFalse($blog->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));
	}

	public function testOnlyMembersCanReplyToGroupDiscussions() {

		$user = $this->mocks->getUser();

		$group_owner = $this->mocks->getUser();
		$group = $this->mocks->getGroup([
			'owner_guid' => $group_owner->guid,
		]);
		$discussion_owner = $this->mocks->getUser();
		$discussion = $this->mocks->getObject([
			'subtype' => 'discussion',
			'owner_guid' => $discussion_owner->guid,
			'container_guid' => $group->guid,
		]);

		$this->assertFalse($discussion->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));

		$this->markTestIncomplete();

		/**
		 * @todo: once relationships are mockable enable the rest of the test
		 */
		$group->join($user);
		$this->assertTrue($discussion->canWriteToContainer($user->guid, 'object', ElggDiscussionReply::SUBTYPE));
	}

}
