<?php

namespace Elgg\DevelopersPlugin;

/**
 * Plugin hook handlers for Developers plugin
 */
class Hooks {

	/**
	 * Alter input of menu sections in "gear" popup
	 *
	 * @param string $hook   'view_vars'
	 * @param string $type   'navigation/menu/elements/section'
	 * @param array  $value  Menu section $vars
	 * @param array  $params Hook params
	 *
	 * @return mixed
	 */
	public static function alterMenuSectionVars($hook, $type, $value, $params) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}

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
	 * @param string $hook   'view'
	 * @param string $type   'navigation/menu/elements/section'
	 * @param array  $output Menu section HTML
	 * @param array  $params Hook params
	 *
	 * @return mixed
	 */
	public static function alterMenuSections($hook, $type, $output, $params) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}

		if (in_array('elgg-developers-gear', $params['vars']['class'])) {
			return "<section>$output</section>";
		}
	}

	/**
	 * Alter output of complete menu in "gear" popup
	 *
	 * @param string $hook   'view'
	 * @param string $type   'navigation/menu/default'
	 * @param array  $output Menu HTML
	 * @param array  $params Hook params
	 *
	 * @return mixed
	 */
	public static function alterMenu($hook, $type, $output, $params) {
		if (!elgg_in_context('developers_gear')) {
			return;
		}

		$output = preg_replace('~^<nav\b[^>]+>~', '', $output);
		$output = preg_replace('~^</nav>$~', '', $output);
		return $output;
	}
	
	/**
	 * Change the to address if a forwarding address isset
	 *
	 * @param \Elgg\Hook $hook The hook for 'prepare', 'system:email'
	 *
	 * @since 3.0
	 * @return void|\Elgg\Email
	 */
	public static function setForwardEmailAddress(\Elgg\Hook $hook) {
		
		if (elgg_get_plugin_setting('block_email', 'developers') !== 'forward') {
			return;
		}
		
		$forward_address = elgg_get_plugin_setting('forward_email', 'developers');
		if (empty($forward_address)) {
			return;
		}
		
		$email = $hook->getValue();
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
	 * @param \Elgg\Hook $hook The hook for 'transport', 'system:email'
	 *
	 * @since 3.0
	 * @return void|true
	 */
	public static function blockOutgoingEmails(\Elgg\Hook $hook) {
		
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
		$email = $hook->getParam('email');
		if (!($email instanceof \Elgg\Email)) {
			return;
		}
		
		$to = $email->getTo();
		$users = get_user_by_email($to->getEmail());
		if (empty($users)) {
			// no user found, so this should be blocked
			// as this e-mail address doesn't belong to any user
			return true;
		}
		
		foreach ($users as $user) {
			if (!$user->isAdmin()) {
				// found a non admin, so block outgoing e-mails
				return true;
			}
		}
	}
}
