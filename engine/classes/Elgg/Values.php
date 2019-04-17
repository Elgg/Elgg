<?php

namespace Elgg;

use Elgg\I18n\DateTime as ElggDateTime;
use DataFormatException;
use DateTime as PHPDateTime;
use Exception;


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
	 * Returns timestamp value of the time representation
	 *
	 * @param \DateTime|\Elgg\I18n\DateTime|string|int $time Time
	 *
	 * @return int
	 * @throws DataFormatException
	 */
	public static function normalizeTimestamp($time) {
		return self::normalizeTime($time)->getTimestamp();
	}

	/**
	 * Returns DateTime object based on time representation
	 *
	 * @param \DateTime|\Elgg\I18n\DateTime|string|int $time Time
	 *
	 * @return \Elgg\I18n\DateTime
	 * @throws DataFormatException
	 */
	public static function normalizeTime($time) {
		try {
			if ($time instanceof ElggDateTime) {
				$dt = $time;
			} elseif ($time instanceof PHPDateTime) {
				$dt = new ElggDateTime($time->format(PHPDateTime::RFC3339_EXTENDED));
			} else if (is_numeric($time)) {
				$dt = new ElggDateTime();
				$dt->setTimestamp((int) $time);
			} else {
				$dt = new ElggDateTime($time);
			}
		} catch (Exception $e) {
			throw new DataFormatException($e->getMessage());
		}

		return $dt;
	}

	/**
	 * Prepare IDs
	 *
	 * @param array ...$args IDs
	 *
	 * @return int[]
	 * @throws DataFormatException
	 */
	public static function normalizeIds(...$args) {
		if (empty($args)) {
			return ELGG_ENTITIES_ANY_VALUE;
		}

		$ids = [];
		foreach ($args as $arg) {
			if (!isset($arg)) {
				continue;
			}
			if (is_object($arg) && isset($arg->id)) {
				$ids[] = (int) $arg->id;
			} else if (is_array($arg)) {
				foreach ($arg as $a) {
					$el_ids = self::normalizeIds($a);
					$ids = array_merge($ids, $el_ids);
				}
			} else if (is_numeric($arg)) {
				$ids[] = (int) $arg;
			} else {
				$arg = print_r($arg, true);
				throw new DataFormatException("Parameter '$arg' can not be resolved to a valid ID'");
			}
		}

		return array_unique($ids);
	}

	/**
	 * Flatten an array of data into an array of GUIDs
	 *
	 * @param mixed ...$args Elements to normalize
	 *
	 * @return int[]|null
	 * @throws DataFormatException
	 */
	public static function normalizeGuids(...$args) {
		if (empty($args)) {
			return ELGG_ENTITIES_ANY_VALUE;
		}

		$guids = [];
		foreach ($args as $arg) {
			if (!isset($arg)) {
				continue;
			}
			if (is_object($arg) && isset($arg->guid)) {
				$guids[] = (int) $arg->guid;
			} else if (is_array($arg)) {
				foreach ($arg as $a) {
					$el_guids = self::normalizeGuids($a);
					$guids = array_merge($guids, $el_guids);
				}
			} else if (is_numeric($arg)) {
				$guids[] = (int) $arg;
			} else {
				$arg = print_r($arg, true);
				throw new DataFormatException("Parameter '$arg' can not be resolved to a valid GUID'");
			}
		}

		return array_unique($guids);
	}

	/**
	 * Return array with __view_output set to prevent view output during view_vars hook
	 *
	 * @see   ViewsService->renderView()
	 *
	 * @return array
	 * @since 3.0
	 */
	public static function preventViewOutput() {
		return ['__view_output' => ''];
	}
	
	/**
	 * Check if a value isn't empty, but allow 0 and '0'
	 *
	 * @param mixed $value the value to check
	 *
	 * @see empty()
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public static function isEmpty($value) {
		
		if ($value === 0 || $value === '0' || $value === 0.0) {
			return false;
		}
		
		return empty($value);
	}
}
