<?php

namespace Elgg\Users;

use Elgg\Email;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Symfony\Component\Mime\Address;

/**
 * Controller to handle confirmation of a user e-mail address change
 *
 * @since 3.1
 */
class EmailChangeController {
	
	/**
	 * Execute an email change
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		$translator = $request->elgg()->translator;
		
		$user = $request->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return elgg_error_response($translator->translate('error:missing_data'));
		}
		
		$new_email = $user->new_email;
		if (empty($new_email)) {
			return elgg_error_response($translator->translate('account:email:request:error:no_new_email'));
		}
		
		unset($user->new_email);
		
		try {
			$request->elgg()->accounts->assertValidEmail($new_email, true);
		} catch (RegistrationException $e) {
			return elgg_error_response($e->getMessage());
		}
		
		$old_email = $user->email;
		$user->email = $new_email;
		$user->save();
		
		// notify old and new email of the change
		$site = elgg_get_site_entity();
		$notification_params = [
			'object' => $user,
			'action' => 'email_change',
		];
		
		$notification = Email::factory([
			'from' => $site,
			'to' => new Address($old_email, $user->getDisplayName()),
			'subject' => $translator->translate('email:confirm:email:old:subject', [], $user->getLanguage()),
			'body' => $translator->translate('email:confirm:email:old:body', [
				$site->getDisplayName(),
				$new_email,
				$site->getURL(),
			], $user->getLanguage()),
			'params' => $notification_params,
		]);
		elgg_send_email($notification);
		
		$user->notify('email_change', $user);
		
		return elgg_ok_response('', $translator->translate('email:save:success'), '');
	}
}
