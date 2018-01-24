<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group Plugins
 * @group SystemLogPlugin
 */
class ViewsRenderingTest extends ViewRenderingTestCase {

	public function up() {
		$this->startPlugin();

		parent::up();
	}

	public function getViewNames() {
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
