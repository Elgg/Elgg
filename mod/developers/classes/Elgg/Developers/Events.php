<?php

namespace Elgg\Developers;

use Symfony\Component\Mime\Address;

/**
 * Event handlers for Developers plugin
 */
class Events {

	/**
	 * Change the to address if a forwarding address isset
	 *
	 * @param \Elgg\Event $event The event for 'prepare', 'system:email'
	 *
	 * @since 3.0
	 * @return void|\Elgg\Email
	 */
	public static function setForwardEmailAddress(\Elgg\Event $event) {
		if (elgg_get_plugin_setting('block_email', 'developers') !== 'forward') {
			return;
		}
		
		$forward_address = (string) elgg_get_plugin_setting('forward_email', 'developers');
		if (!elgg_is_valid_email($forward_address)) {
			return;
		}
		
		$email = $event->getValue();
		if (!$email instanceof \Elgg\Email) {
			return;
		}
		
		$change_address = function(array $addresses) use ($forward_address) {
			$new_addresses = [];
			/** @var Address $address */
			foreach ($addresses as $address) {
				$new_addresses[] = new Address($forward_address, $address->getName());
			}
			
			return $new_addresses;
		};
		
		$addresses = $change_address($email->getTo());
		$email->setTo($addresses);
		
		$addresses = $change_address($email->getCc());
		$email->setCc($addresses);
		
		$addresses = $change_address($email->getBcc());
		$email->setBcc($addresses);
		
		return $email;
	}
	
	/**
	 * Block outgoing emails
	 *
	 * @param \Elgg\Event $event The event for 'transport', 'system:email'
	 *
	 * @since 3.0
	 * @return void|true
	 */
	public static function blockOutgoingEmails(\Elgg\Event $event) {
		$block_setting = elgg_get_plugin_setting('block_email', 'developers');
		if (!in_array($block_setting, ['all', 'users'])) {
			// don't block outgoing e-mails
			return;
		}
		
		if ($block_setting === 'all') {
			// block all outgoing e-mails
			return true;
		}
		
		// only block outgoing e-mails for regular users
		// so check if the receiver is an admin
		$email = $event->getParam('email');
		if (!$email instanceof \Elgg\Email) {
			return;
		}
		
		$filter = function(Address $address) {
			$user = elgg_get_user_by_email($address->getAddress());
			if (!$user instanceof \ElggUser) {
				// no user found, so filter out this address
				// as this e-mail address doesn't belong to any user
				return false;
			}
			
			if (!$user->isAdmin()) {
				// found a non admin, so filter out the address
				return false;
			}
			
			return true;
		};
		
		$addresses = $email->getTo();
		$to_addresses = array_filter($addresses, $filter);
		$email->setTo($to_addresses);
		
		$addresses = $email->getCc();
		$cc_addresses = array_filter($addresses, $filter);
		$email->setCc($cc_addresses);
		
		$addresses = $email->getBcc();
		$bcc_addresses = array_filter($addresses, $filter);
		$email->setBcc($bcc_addresses);
		
		if (empty($to_addresses) && empty($cc_addresses) && empty($bcc_addresses)) {
			// filtered out all the recipients, handle as sent
			return true;
		}
	}
}
