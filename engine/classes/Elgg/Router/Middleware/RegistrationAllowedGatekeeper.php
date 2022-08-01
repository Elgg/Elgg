<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\Gatekeeper\RegistrationAllowedGatekeeperException;
use Elgg\Request;

/**
 * Validate that registration is allowed based on one of the following:
 * - valid invite code
 * - registration enabled
 *
 * @since 4.3
 */
class RegistrationAllowedGatekeeper {
	
	/**
	 * Validate input and/or settings to allow access to registration resources
	 *
	 * @param Request $request the current request
	 *
	 * @return void
	 * @throws RegistrationAllowedGatekeeperException
	 */
	public function __invoke(Request $request): void {
		if ($this->validateInviteCode($request)) {
			return;
		}
		
		$this->assertRegistrationEnabled();
	}
	
	/**
	 * Validate the optional invite code
	 *
	 * @param Request $request the current request
	 *
	 * @return bool
	 * @throws RegistrationAllowedGatekeeperException
	 * @see elgg_validate_invite_code()
	 */
	protected function validateInviteCode(Request $request): bool {
		$friend_guid = (int) $request->getParam('friend_guid');
		$invitecode = $request->getParam('invitecode');
		
		if (empty($friend_guid) || empty($invitecode)) {
			return false;
		}
		
		$friend = get_user($friend_guid);
		if (!$friend instanceof \ElggUser) {
			return false;
		}
		
		if (elgg_validate_invite_code($friend->username, $invitecode)) {
			return true;
		}
		
		throw new RegistrationAllowedGatekeeperException(elgg_echo('RegistrationAllowedGatekeeperException:invalid_invitecode'));
	}
	
	/**
	 * Validate if registration is allowed by the site configuration
	 *
	 * @return void
	 * @throws RegistrationAllowedGatekeeperException
	 */
	protected function assertRegistrationEnabled(): void {
		if (_elgg_services()->config->allow_registration) {
			return;
		}
		
		throw new RegistrationAllowedGatekeeperException();
	}
}
