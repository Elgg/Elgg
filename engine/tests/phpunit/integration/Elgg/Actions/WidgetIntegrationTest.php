<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * @group ActionsService
 * @group WidgetActions
 */
class WidgetsIntegrationTest extends ActionResponseTestCase {
	/**
	 * @var ElggWidget
	 */
	protected $widget;

	/**
	 * @var ElggUser
	 */
	protected $user;
	
	public function up() {
		$this->user = $this->createUser();
		
		_elgg_services()->session->setLoggedInUser($this->user);
		$this->widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		
		_elgg_services()->hooks->backup();
	}

	public function down() {
		if (isset($this->widget)) {
			$this->widget->delete();
		}
		if (isset($this->user)) {
			$this->user->delete();
		}
		
		_elgg_services()->session->removeLoggedInUser();
		_elgg_services()->hooks->restore();
	}
	
	public function testWidgetAddFailsWithMissingPageOwner() {
		$response = $this->executeAction('widgets/add');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:add:failure'), $response->getContent());
	}
	
	public function testWidgetAddFailsIfCantEditContext() {
		$response = $this->executeAction('widgets/add', [
			'page_owner_guid' => $this->user->guid,
			'context' => 'invalid_context',
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:add:failure'), $response->getContent());
	}
	
	public function testWidgetAddFailsCantCreate() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getTrue');
		
		$response = $this->executeAction('widgets/add', [
			'page_owner_guid' => $this->user->guid,
			'context' => 'invalid_context',
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:add:failure'), $response->getContent());
	}
	
	public function testWidgetAddSuccess() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getTrue');
		
		$other_user = $this->createUser();
		
		elgg_register_widget_type([
			'id' => 'my_widget',
			'page_owner_guid' => $other_user->guid,
			'context' => ['my_context'],
		]);
		$response = $this->executeAction('widgets/add', [
			'page_owner_guid' => $this->user->guid,
			'handler' => 'my_widget',
			'context' => 'my_context',
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		elgg_unregister_widget_type('my_handler');
	}

	// delete action
	public function testWidgetDeleteFailsIfNoWidget() {
		$response = $this->executeAction('widgets/delete');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:remove:failure'), $response->getContent());

		$response = $this->executeAction('widgets/delete', [
			'widget_guid' => $this->user->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:remove:failure'), $response->getContent());
	}

	public function testWidgetDeleteFailsIfCantEditContext() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getFalse');
		$response = $this->executeAction('widgets/delete', [
			'widget_guid' => $this->widget->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:remove:failure'), $response->getContent());
	}

	public function testWidgetDeleteFailsCantDelete() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getTrue');
		
		$other_user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($other_user);
		$widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		_elgg_services()->session->setLoggedInUser($this->user);
		
		$response = $this->executeAction('widgets/delete', [
			'widget_guid' => $widget->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:remove:failure'), $response->getContent());
		
		$ia = elgg_set_ignore_access(true);
		$widget->delete();
		$other_user->delete();
		elgg_set_ignore_access($ia);
	}

	public function testWidgetDeleteSuccess() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getTrue');
		
		$widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		
		$response = $this->executeAction('widgets/delete', [
			'widget_guid' => $widget->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
	}
	
	// move action
	public function testWidgetMoveFailsIfNoWidget() {
		$response = $this->executeAction('widgets/move');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:move:failure'), $response->getContent());

		$response = $this->executeAction('widgets/move', [
			'widget_guid' => $this->user->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:move:failure'), $response->getContent());
	}

	public function testWidgetMoveFailsIfCantEditContext() {
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\Elgg\Values::getFalse');
		
		$response = $this->executeAction('widgets/move', [
			'widget_guid' => $this->widget->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:move:failure'), $response->getContent());
	}

	public function testWidgetMoveSuccess() {
		$response = $this->executeAction('widgets/move', [
			'widget_guid' => $this->widget->guid,
			'column' => 3,
			'rank' => 2,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
	}
	
	// save
	public function testWidgetSaveFailsIfNoWidget() {
		$response = $this->executeAction('widgets/save');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:save:failure'), $response->getContent());

		$response = $this->executeAction('widgets/save', [
			'widget_guid' => $this->user->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:save:failure'), $response->getContent());
	}
	
	public function testWidgetSaveFailsIfCantEdit() {
		$other_user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($other_user);
		$widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		_elgg_services()->session->setLoggedInUser($this->user);
		
		$response = $this->executeAction('widgets/save', [
			'widget_guid' => $widget->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('widgets:save:failure'), $response->getContent());
	}
	
	public function testWidgetSaveSuccess() {
		$response = $this->executeAction('widgets/save', [
			'widget_guid' => $this->widget->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);
	}
}
