<?php

namespace Elgg\Actions\Admin;

use Elgg\ActionResponseTestCase;
use Elgg\EntityNotFoundException;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * @group UpgradeService
 * @group UpgradeAction
 * @group Admin
 */
class UpgradeTest extends ActionResponseTestCase {

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testUpgradeFailsWithInvalidUpgradeEntity() {
		$this->executeAction('admin/upgrade', [
			'guid' => -5,
		]);
	}

	public function testUpgradeSucceeds() {
		elgg_delete_admin_notice('pending_upgrades');

		$batch = new UpgradeTestBatch();
		$version = $batch->getVersion();

		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(UpgradeTestBatch::class);
		$upgrade->setId("test_plugin:$version");
		$upgrade->title = "test_plugin:upgrade:$version:title";
		$upgrade->description = "test_plugin:upgrade:$version:title";
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$this->assertTrue(elgg_admin_notice_exists('pending_upgrades'));

		$response = $this->executeAction('admin/upgrade', [
			'guid' => $upgrade->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertEquals([
			'errors' => [],
			'numErrors' => 0,
			'numSuccess' => 100,
			'isComplete' => true,
		], $response->getContent());

		$this->assertTrue($upgrade->isCompleted());
		$this->assertEmpty(_elgg_services()->upgrades->getPendingUpgrades());
		$this->assertFalse(elgg_admin_notice_exists('pending_upgrades'));
	}
}

class UpgradeTestBatch implements AsynchronousUpgrade {

	protected $_version;

	public function getVersion() {
		if (!isset($this->_version)) {
			$this->_version = date('Ymd') . rand(10, 99);
		}

		return $this->_version;
	}

	public function needsIncrementOffset() {
		return true;
	}

	public function shouldBeSkipped() {
		return false;
	}

	public function countItems() {
		return 100;
	}

	public function run(Result $result, $offset) {
		$result->addSuccesses(10);
	}
}
