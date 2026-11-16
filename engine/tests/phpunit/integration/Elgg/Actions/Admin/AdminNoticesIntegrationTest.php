<?php

namespace Elgg\Actions\Admin;

use Elgg\ActionResponseTestCase;
use Elgg\Http\OkResponse;

class AdminNoticesIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();
		
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());
	}

	public function testDeletesSingleAdminNotice() {

		_elgg_services()->adminNotices->delete('zen');

		$notice = _elgg_services()->adminNotices->add('zen', 'Maybe the forces of the universe be with you');
		$this->assertInstanceOf(\ElggAdminNotice::class, $notice);
		$this->assertEquals('zen', $notice->admin_notice_id);

		$this->assertTrue(_elgg_services()->adminNotices->exists('zen'));

		$response = $this->executeAction('entity/delete', [
			'guid' => $notice->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertFalse(_elgg_services()->adminNotices->exists('zen'));
	}

	public function testDeletesBatchAdminNotices() {

		_elgg_services()->adminNotices->add('zen1', 'Maybe the forces of the universe be with you');
		_elgg_services()->adminNotices->add('zen2', 'Maybe the forces of the universe be with you');
		_elgg_services()->adminNotices->add('zen3', 'Maybe the forces of the universe be with you');

		$count_zens = function() {
			return _elgg_services()->adminNotices->find([
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
