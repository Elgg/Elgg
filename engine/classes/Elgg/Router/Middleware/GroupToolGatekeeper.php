<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\Gatekeeper\GroupToolGatekeeperException;
use Elgg\Exceptions\HttpException;

/**
 * Check if the current route has a group_tool configured and that the group tool is enabled for the (group) page owner
 *
 * @since 7.0
 */
class GroupToolGatekeeper extends GroupPageOwnerGatekeeper {
	
	/**
	 * {@inheritdoc}
	 * @throws GroupToolGatekeeperException
	 * @throws HttpException
	 */
	public function __invoke(\Elgg\Request $request): void {
		parent::__invoke($request);
		
		$group_tool = $request->getHttpRequest()?->getRoute()?->getOption('group_tool');
		if (empty($group_tool)) {
			return;
		}
		
		_elgg_services()->gatekeeper->assertGroupToolEnabled($group_tool, $this->page_owner);
	}
}
