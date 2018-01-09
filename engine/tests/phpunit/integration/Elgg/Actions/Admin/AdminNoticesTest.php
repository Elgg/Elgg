<?php

namespace Elgg\Actions\Admin;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * @group ActionsService
 * @group AdminNotices
 * @group Admin
 */
class AdminNoticesTest extends ActionResponseTestCase {

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testDeletesSingleAdminNotice() {

		elgg_delete_admin_notice('zen');

		$notice = elgg_add_admin_notice('zen', 'Maybe the forces of the universe be with you');
		$this->assertInstanceOf(\ElggObject::class, $notice);
		$this->assertEquals('zen', $notice->admin_notice_id);

		$this->assertTrue(elgg_admin_notice_exists('zen'));

		$response = $this->executeAction('admin/delete_admin_notice', [
			'guid' => $notice->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertFalse(elgg_admin_notice_exists('zen'));
	}

	public function testDeletesSingleAdminNoticeWithEmptyID() {

		$notice = elgg_add_admin_notice('will_be_removed', 'foo');
		$this->assertInstanceOf(\ElggObject::class, $notice);
		$this->assertEquals('will_be_removed', $notice->admin_notice_id);

		$notice->admin_notice_id = '';
		
		$this->assertTrue(elgg_admin_notice_exists(''));

		$this->assertTrue(elgg_delete_admin_notice(''));
		$this->assertFalse(elgg_admin_notice_exists(''));
	}

	public function testIgnoresNonAdminNoticesDuringDeleteAction() {

		$object = $this->createObject();

		$response = $this->executeAction('admin/delete_admin_notice', [
			'guid' => $object->guid,
		]);

		$this->assertInstanceOf(ErrorResponse::class, $response);
	}

	public function testDeletesBatchAdminNotices() {

		elgg_add_admin_notice('zen1', 'Maybe the forces of the universe be with you');
		elgg_add_admin_notice('zen2', 'Maybe the forces of the universe be with you');
		elgg_add_admin_notice('zen3', 'Maybe the forces of the universe be with you');

		$count_zens = function() {
			return elgg_get_admin_notices([
				'metadata_name_value_pairs' => [
					'name' => 'admin_notice_id',
					'value' => 'zen%',
					'operand' => 'LIKE',
				],
				'count' => true,
			]);
		};

		$this->assertEquals(3, $count_zens());

		$response = $this->executeAction('admin/delete_admin_notices');

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertEquals(0, $count_zens());
	}

}