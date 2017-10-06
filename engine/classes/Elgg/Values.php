<?php
namespace Elgg;

/**
 * Functions for use as plugin hook/event handlers or other situations where you need a
 * globally accessible callable.
 */
class Values {

	/**
	 * Return true
	 *
	 * @return true
	 * @since 1.12.0
	 */
	public static function getTrue() {
		return true;
	}

	/**
	 * Return false
	 *
	 * @return false
	 * @since 1.12.0
	 */
	public static function getFalse() {
		return false;
	}

	/**
	 * Return null
	 *
	 * @return null
	 * @since 1.12.0
	 */
	public static function getNull() {
	}

	/**
	 * Return empty array
	 *
	 * @return array
	 * @since 1.12.0
	 */
	public static function getArray() {
		return [];
	}

	/**
	 * Return array with __view_output set to prevent view output during view_vars hook
	 *
	 * @see ViewsService->renderView()
	 *
	 * @return array
	 * @since 3.0
	 */
	public static function preventViewOutput() {
		return ['__view_output' => ''];
	}
}
