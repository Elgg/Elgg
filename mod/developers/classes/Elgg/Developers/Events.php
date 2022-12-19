<?php

namespace Elgg\Developers;

/**
 * Event handlers for Developers plugin
 */
class Events {

	/**
	 * Alter input of menu sections in "gear" popup
	 *
	 * @param \Elgg\Event $event 'view_vars', 'navigation/menu/elements/section'
	 *
	 * @return mixed
	 */
	public static function alterMenuSectionVars(\Elgg\Event $event) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}
		
		$value = $event->getValue();
		$idx = array_search('elgg-menu-page', $value['class']);
		if ($idx !== false) {
			unset($value['class'][$idx]);
			$value['class'][] = 'elgg-menu-gear';
		}

		// remove the display options
		foreach ($value['items'] as $item) {
			/* @var \ElggMenuItem $item  */
			$child_opts = $item->getChildMenuOptions();
			unset($child_opts['display']);
			$item->setChildMenuOptions($child_opts);
		}

		return $value;
	}

	/**
	 * Alter output of menu sections in "gear" popup
	 *
	 * @param \Elgg\Event $event 'view', 'navigation/menu/elements/section'
	 *
	 * @return mixed
	 */
	public static function alterMenuSections(\Elgg\Event $event) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}
		
		$params = $event->getParams();
		if (in_array('elgg-developers-gear', $params['vars']['class'])) {
			return elgg_format_element('section', [], $event->getValue());
		}
	}

	/**
	 * Alter output of complete menu in "gear" popup
	 *
	 * @param \Elgg\Event $event 'view', 'navigation/menu/default'
	 *
	 * @return mixed
	 */
	public static function alterMenu(\Elgg\Event $event) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}
		
		$output = $event->getValue();
		$output = preg_replace('~^<nav\b[^>]+>~', '', $output);
		$output = preg_replace('~^</nav>$~', '', $output);
		
		return $output;
	}
	
	/**
	 * Change the to address if a forwarding address isset
	 *
	 * @param \Elgg\Event $event The hook for 'prepare', 'system:email'
	 *
	 * @since 3.0
	 * @return void|\Elgg\Email
	 */
	public static function setForwardEmailAddress(\Elgg\Event $event) {
		if (elgg_get_plugin_setting('block_email', 'developers') !== 'forward') {
			return;
		}
		
		$forward_address = elgg_get_plugin_setting('forward_email', 'developers');
		if (empty($forward_address)) {
			return;
		}
		
		$email = $event->getValue();
		if (!($email instanceof \Elgg\Email)) {
			return;
		}
		
		$to = $email->getTo();
		$to->setEmail($forward_address);
		
		$email->setTo($to);
		
		return $email;
	}
	
	/**
	 * Block outgoing emails
	 *
	 * @param \Elgg\Event $event The hook for 'transport', 'system:email'
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
		
		$to = $email->getTo();
		$user = elgg_get_user_by_email($to->getEmail());
		if (!$user instanceof \ElggUser) {
			// no user found, so this should be blocked
			// as this e-mail address doesn't belong to any user
			return true;
		}
		
		if (!$user->isAdmin()) {
			// found a non admin, so block outgoing e-mails
			return true;
		}
	}
}
