<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Regression coverage for the two production-dominant metadata query shapes.
 *
 * These are the metadata reads the upcoming composite `(entity_guid, name)`
 * index on the `metadata` table targets, exercised through their real API
 * entry points:
 *
 *   (a) the comment thread lookup — the single most common metadata shape in
 *       the codebase (19 call sites): elgg_get_entities() filtered by
 *       type=object, subtype=comment and a `parent_guid` name/value pair.
 *       See \ElggComment::deleteThreadedComments()/restore() and every
 *       comment-thread reader.
 *
 *   (b) the widget context lookup: elgg_get_entities() filtered by
 *       type=object, subtype=widget, an owner/container scope and a `context`
 *       metadata filter. See \Elgg\WidgetsService::getWidgets() and
 *       \ElggWidget::move().
 *
 * The index must not change results, so we lock the behaviour in first. Each
 * scenario asserts the EXACT result set (so an index-aware refactor that
 * widened or narrowed the match would fail) and that entity access is still
 * enforced (the metadata table has no access_id of its own — access comes from
 * the joined `entities` row — so a private comment/widget must stay hidden from
 * a stranger).
 */
class MetadataHotShapesRegressionTest extends IntegrationTestCase {

	protected \ElggUser $owner;

	protected \ElggUser $stranger;

	protected string $container_subtype = 'metadata_hot_shapes_container';

	public function up() {
		$this->owner = $this->createUser();
		$this->stranger = $this->createUser();

		_elgg_services()->session_manager->setLoggedInUser($this->owner);

		// comments can only be written into a container that has the `commentable`
		// capability (Elgg\Comments\ContainerLogicHandler); enable it for the
		// dedicated container subtype so the comment fixtures can be created.
		elgg_entity_enable_capability('object', $this->container_subtype, 'commentable');
	}

	public function down() {
		elgg_entity_disable_capability('object', $this->container_subtype, 'commentable');
		// created users/objects are auto-cleaned by the Seeding trait
	}

	/**
	 * Helper: create a commentable container object owned by the owner.
	 */
	protected function createContainer(): \ElggObject {
		return $this->createObject([
			'subtype' => $this->container_subtype,
			'owner_guid' => $this->owner->guid,
			'container_guid' => $this->owner->guid,
			'access_id' => ACCESS_PUBLIC,
		]);
	}

	/**
	 * Helper: create a comment (subtype 'comment') carrying a parent_guid.
	 */
	protected function createComment(int $parent_guid, int $container_guid, int $access_id = ACCESS_PUBLIC): \ElggObject {
		$comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => $this->owner->guid,
			'container_guid' => $container_guid,
			'access_id' => $access_id,
		]);

		$comment->parent_guid = $parent_guid;

		return $comment;
	}

	/**
	 * Helper: create a widget (subtype 'widget') carrying a context.
	 */
	protected function createWidget(int $owner_guid, string $context, int $access_id = ACCESS_PUBLIC): \ElggObject {
		return elgg_call(ELGG_IGNORE_ACCESS, function () use ($owner_guid, $context, $access_id) {
			$widget = $this->createObject([
				'subtype' => 'widget',
				'owner_guid' => $owner_guid,
				'container_guid' => $owner_guid,
				'access_id' => $access_id,
			]);

			$widget->context = $context;

			return $widget;
		});
	}

	/**
	 * Helper: guids of the returned entities, sorted for order-independent compare.
	 *
	 * @param \ElggEntity[] $entities entities returned by a query
	 *
	 * @return int[]
	 */
	protected function guids(array $entities): array {
		$guids = array_map(function ($e) {
			return (int) $e->guid;
		}, $entities);
		sort($guids);

		return $guids;
	}

	/**
	 * Comment thread lookup returns exactly the comments whose parent_guid points
	 * at the queried container, and nothing belonging to a different parent.
	 *
	 * Mirrors \ElggComment::deleteThreadedComments() (ElggComment.php:95-105) and
	 * \ElggComment::restore() (ElggComment.php:62-71).
	 */
	public function testCommentByParentGuidReturnsExactSet() {
		$container_a = $this->createContainer();
		$container_b = $this->createContainer();

		$a1 = $this->createComment($container_a->guid, $container_a->guid);
		$a2 = $this->createComment($container_a->guid, $container_a->guid);
		$a3 = $this->createComment($container_a->guid, $container_a->guid);

		// noise: a comment on a different parent must never surface
		$this->createComment($container_b->guid, $container_b->guid);

		$options = [
			'type' => 'object',
			'subtype' => 'comment',
			'metadata_name_value_pairs' => [
				'name' => 'parent_guid',
				'value' => $container_a->guid,
			],
			'limit' => false,
		];

		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});

		$this->assertEquals(
			$this->guids([$a1, $a2, $a3]),
			$this->guids($found),
			'by-parent_guid lookup did not return exactly the comments of that parent'
		);
	}

	/**
	 * A private comment on the thread is hidden from a stranger but visible to the
	 * owner (and under ignored access). The metadata table has no access_id of its
	 * own, so this proves the entities access join is still applied.
	 *
	 * Same query shape as \ElggComment::deleteThreadedComments() (ElggComment.php:95-105).
	 */
	public function testCommentByParentGuidRespectsAccess() {
		$container = $this->createContainer();

		$public = $this->createComment($container->guid, $container->guid, ACCESS_PUBLIC);
		$private = $this->createComment($container->guid, $container->guid, ACCESS_PRIVATE);

		$options = [
			'type' => 'object',
			'subtype' => 'comment',
			'metadata_name_value_pairs' => [
				'name' => 'parent_guid',
				'value' => $container->guid,
			],
			'limit' => false,
		];

		// stranger: only the public comment
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals(
			$this->guids([$public]),
			$this->guids($found),
			'private comment leaked to an unauthorized viewer via parent_guid lookup'
		);

		// owner: both comments
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($this->guids([$public, $private]), $this->guids($found));

		// ignore access: both comments regardless of viewer
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($this->guids([$public, $private]), $this->guids($found));
	}

	/**
	 * Widget context lookup returns exactly the widgets with the queried context on
	 * the queried owner — excluding a different context and a different owner.
	 *
	 * Mirrors \Elgg\WidgetsService::getWidgets() (WidgetsService.php:47-54) and
	 * \ElggWidget::move() (ElggWidget.php:47-56).
	 */
	public function testWidgetByContextReturnsExactSet() {
		$other_owner = $this->createUser();

		$dash1 = $this->createWidget($this->owner->guid, 'dashboard');
		$dash2 = $this->createWidget($this->owner->guid, 'dashboard');

		// noise: same owner, different context
		$this->createWidget($this->owner->guid, 'profile');
		// noise: same context, different owner
		$this->createWidget($other_owner->guid, 'dashboard');

		$options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $this->owner->guid,
			'metadata_name_value_pairs' => [
				'name' => 'context',
				'value' => 'dashboard',
			],
			'limit' => false,
		];

		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});

		$this->assertEquals(
			$this->guids([$dash1, $dash2]),
			$this->guids($found),
			'by-context lookup did not return exactly the widgets with that context on that owner'
		);
	}

	/**
	 * The same shape expressed with metadata_name + metadata_value — the exact
	 * option form \Elgg\WidgetsService::getWidgets() uses (WidgetsService.php:47-54) —
	 * returns the identical result set.
	 */
	public function testWidgetByContextViaMetadataNameValueMatchesPairForm() {
		$dash1 = $this->createWidget($this->owner->guid, 'dashboard');
		$dash2 = $this->createWidget($this->owner->guid, 'dashboard');
		$this->createWidget($this->owner->guid, 'profile');

		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () {
			return elgg_get_entities([
				'type' => 'object',
				'subtype' => 'widget',
				'owner_guid' => $this->owner->guid,
				'metadata_name' => 'context',
				'metadata_value' => 'dashboard',
				'limit' => false,
			]);
		});

		$this->assertEquals(
			$this->guids([$dash1, $dash2]),
			$this->guids($found),
			'metadata_name/metadata_value widget-by-context lookup returned an unexpected set'
		);
	}

	/**
	 * A private widget is hidden from a stranger but visible to the owner (and
	 * under ignored access), proving the entities access join still governs the
	 * widget-by-context read path.
	 *
	 * Same query shape as \Elgg\WidgetsService::getWidgets() (WidgetsService.php:47-54).
	 */
	public function testWidgetByContextRespectsAccess() {
		$public = $this->createWidget($this->owner->guid, 'dashboard', ACCESS_PUBLIC);
		$private = $this->createWidget($this->owner->guid, 'dashboard', ACCESS_PRIVATE);

		$options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $this->owner->guid,
			'metadata_name_value_pairs' => [
				'name' => 'context',
				'value' => 'dashboard',
			],
			'limit' => false,
		];

		// stranger: only the public widget
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals(
			$this->guids([$public]),
			$this->guids($found),
			'private widget leaked to an unauthorized viewer via context lookup'
		);

		// owner: both widgets
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
		$found = elgg_call(ELGG_ENFORCE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($this->guids([$public, $private]), $this->guids($found));

		// ignore access: both widgets regardless of viewer
		_elgg_services()->session_manager->setLoggedInUser($this->stranger);
		$found = elgg_call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options);
		});
		$this->assertEquals($this->guids([$public, $private]), $this->guids($found));
	}
}
