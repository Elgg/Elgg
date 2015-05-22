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
		// I would avoid using context, but we have to use it already for alterMenuSections()
		if (!elgg_in_context('developers_gear')) {
			return;
		}

		$value['class'] = preg_replace('~(^|\\s)elgg-menu-page($|\\s)~', '$1elgg-menu-gear$2', $value['class']);
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
		// I tried avoiding using context, but not enough data is passed down into
		// this hook to reason if we're in the gear popup view
		if (!elgg_in_context('developers_gear')) {
			return;
		}

		if (false === strpos($params['vars']['class'], 'elgg-child-menu')) {
			return "<section>$output</section>";
		}
	}
}
