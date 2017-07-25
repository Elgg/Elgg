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
}
