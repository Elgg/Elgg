<?php

namespace Elgg\Views;

use Elgg\ViewRenderingTestCase;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group Widgets
 */
class WidgetViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		$views = [
			'forms/widgets/save',
			'object/widget',
			'object/widget/edit/num_display',
			'object/widget/elements/content',
			'object/widget/elements/controls',
			'object/widget/elements/settings',
			'object/widget/body',
			'object/widget/header',
		];

		$widget_types = [
			'admin_welcome',
			'banned_users',
			'content_stats',
			'control_panel',
			'cron_status',
			'new_users',
			'online_users',
		];

		foreach ($widget_types as $widget_type) {
			$views[] = "widgets/$widget_type/content";
			$views[] = "widgets/$widget_type/edit";
		}

		return $views;
	}

	public function getDefaultViewVars() {
		$widget = $this->createObject([
			'subtype' => 'widget',
		]);
		return [
			'entity' => $widget,
		];
	}
}
