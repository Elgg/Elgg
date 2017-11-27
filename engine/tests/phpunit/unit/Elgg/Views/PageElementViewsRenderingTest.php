<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class PageElementViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'errors/400',
			'errors/403',
			'errors/404',
			'errors/default',
			'page/admin',
			'page/default',
			'page/error',
			'page/maintenance',
			'page/upgrade',
			'page/walled_garden',
			'page/elements/walled_garden/footer',
			'page/elements/walled_garden/header',
			'page/elements/admin_notices',
			'page/elements/body',
			'page/elements/comments',
			'page/elements/comments_block',
			'page/elements/foot',
			'page/elements/footer',
			'page/elements/head',
			'page/elements/header',
			'page/elements/header_logo',
			'page/elements/html',
			'page/elements/messages',
			'page/elements/navbar',
			'page/elements/owner_block',
			'page/elements/section',
			'page/elements/sidebar',
			'page/elements/sidebar_alt',
			'page/elements/title',
			'page/elements/topbar',
			'page/layouts/elements/body',
			'page/layouts/elements/breadcrumbs',
			'page/layouts/elements/content',
			'page/layouts/elements/filter',
			'page/layouts/elements/footer',
			'page/layouts/elements/header',
			'page/layouts/elements/sidebar',
			'page/layouts/elements/sidebar_alt',
			'page/layouts/widgets/add_button',
			'page/layouts/admin',
			'page/layouts/default',
			'page/layouts/error',
			'page/layouts/maintenance',
			'page/layouts/widgets',
			'page/components/image_block',
			'page/components/message',
			'page/components/module',
			'page/components/no_results',
			'page/components/tabs',
		];
	}

	public function getDefaultViewVars() {
		return [
			'content' => '',
			'title' => '',
		];
	}
}
