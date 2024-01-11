<?php

namespace Elgg\SystemLog;

use Elgg\Views\ViewRenderingIntegrationTestCase;

class ViewsRenderingIntegrationTest extends ViewRenderingIntegrationTestCase {

	public function up() {
		parent::up();
		
		$this->startPlugin();
	}

	public static function getViewNames() {
		return [
			'admin/administer_utilities',
			'core/settings/account/login_history',
			'forms/logbrowser/refine',
			'logbrowser/refine',
			'logbrowser/table',
			'plugins/system_log/settings',
		];
	}

	public function getDefaultViewVars() {
		return [];
	}
}
