<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class UserViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'core/avatar/crop',
			'core/avatar/upload',
			'core/settings/account/default_access',
			'core/settings/account/email',
			'core/settings/account/language',
			'core/settings/account/name',
			'core/settings/account/notifications',
			'core/settings/account/password',
			'core/settings/account/username',
			'core/settings/statistics/numentities',
			'core/settings/statistics/online',
			'core/settings/account',
			'core/settings/statistics',
			'icon/user/default',
			'forms/avatar/crop',
			'forms/avatar/edit',
			'forms/user/changepassword',
			'forms/user/requestnewpassword',
			'forms/usersettings/save',
			'input/friendspicker',
			'page/components/column/admin',
			'page/components/column/banned',
			'page/components/column/container',
			'page/components/column/excerpt',
			'page/components/column/icon',
			'page/components/column/item',
			'page/components/column/language',
			'page/components/column/link',
			'page/components/column/owner',
			'page/components/column/time_created',
			'page/components/column/time_updated',
			'page/components/column/user',
			'resources/account/change_password',
			'resources/account/forgotten_password',
			'resources/avatar/edit',
			'user/default',
			'user/default/column',
			'user/elements/summary',
		];
	}

	public function getDefaultViewVars() {
		$user = $this->createUser();
		return [
			'item' => $user,
			'entity' => $user,
			'user' => $user,
			'guid' => $user->guid,
		];
	}
}
