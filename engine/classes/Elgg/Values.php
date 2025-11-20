<?php

namespace Elgg;

use Elgg\I18n\DateTime as ElggDateTime;
use Elgg\Exceptions\DataFormatException;

/**
 * Functions for use as event handlers or other situations where you need a
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
		return null;
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
	 * @param \DateTimeInterface|string|int $time Time
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
	 * @param \DateTimeInterface|string|int $time Time
	 *
	 * @return \Elgg\I18n\DateTime
	 * @throws DataFormatException
	 */
	public static function normalizeTime($time) {
		try {
			if ($time instanceof ElggDateTime) {
				$dt = $time;
			} elseif ($time instanceof \DateTimeInterface) {
				$dt = new ElggDateTime($time->format(\DateTimeInterface::RFC3339_EXTENDED));
			} elseif (is_numeric($time)) {
				$dt = new ElggDateTime();
				$dt->setTimestamp((int) $time);
			} elseif (is_string($time)) {
				$dt = new ElggDateTime($time);
			} else {
				$dt = new ElggDateTime();
			}
		} catch (\Exception $e) {
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
			} elseif (is_array($arg)) {
				foreach ($arg as $a) {
					$el_ids = self::normalizeIds($a);
					$ids = array_merge($ids, $el_ids);
				}
			} elseif (is_numeric($arg)) {
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
			} elseif (is_array($arg)) {
				foreach ($arg as $a) {
					$el_guids = self::normalizeGuids($a);
					$guids = array_merge($guids, $el_guids);
				}
			} elseif (is_numeric($arg)) {
				$guids[] = (int) $arg;
			} else {
				$arg = print_r($arg, true);
				throw new DataFormatException("Parameter '$arg' can not be resolved to a valid GUID'");
			}
		}

		return array_unique($guids);
	}

	/**
	 * Return array with __view_output set to prevent view output during view_vars event
	 *
	 * @see ViewsService->renderView()
	 *
	 * @return array
	 * @since 3.0
	 */
	public static function preventViewOutput() {
		return [ViewsService::OUTPUT_KEY => ''];
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
	public static function isEmpty($value): bool {
		if ($value === 0 || $value === '0' || $value === 0.0) {
			return false;
		}
		
		return empty($value);
	}
	
	/**
	 * Use to convert large positive numbers in to short form like 1K, 1M, 1B or 1T
	 * Example: shortFormatOutput(7201); // Output: 7K
	 * Example: shortFormatOutput(7201,1); // Output: 7.2K
	 *
	 * @param mixed $n        input integer or string
	 * @param int   $decimals number of digits in decimal place (default = 0)
	 *
	 * @return string|int
	 * @since 3.1
	 */
	public static function shortFormatOutput($n, int $decimals = 0) {
		// return the input if not a number
		if (!is_numeric($n)) {
			return $n;
		}
		
		// remove negative sign
		$negative = abs($n) !== $n;
		$n = abs($n);
		
		$decimal_separator = substr(elgg_echo('number_counter:decimal_separator'), 0, 1);
		$text_key = null;
		
		if ($n < 1000) {
			$n = self::numberFormat($n, $decimals);
		} elseif ($n < 1000000) {
			// 1.5K, 999.5K
			$n = self::numberFormat($n / 1000, $decimals);
			$text_key = 'number_counter:view:thousand';
		} elseif ($n < 1000000000) {
			// 1.5M, 999.5M
			$n = self::numberFormat($n / 1000000, $decimals);
			$text_key = 'number_counter:view:million';
		} elseif ($n < 1000000000000) {
			// 1.5B, 999.5B
			$n = self::numberFormat($n / 1000000000, $decimals);
			$text_key = 'number_counter:view:billion';
		} else {
			// 1.5T
			$n = self::numberFormat($n / 1000000000000, $decimals);
			$text_key = 'number_counter:view:trillion';
		}
		
		if (stristr($n, $decimal_separator) !== false) {
			// strip trailing zero's after decimal separator
			$parts = explode($decimal_separator, $n);
			$parts[1] = rtrim($parts[1], 0);
			
			$n = implode($decimal_separator, array_filter($parts));
		}
		
		// restore negative sign
		$n = $negative ? "-{$n}" : $n;
		
		return $text_key ? elgg_echo($text_key, [$n]) : $n;
	}
	
	/**
	 * Format a number with grouped thousands using language specific separators
	 *
	 * @param float $number   The number being formatted
	 * @param int   $decimals (optional) Sets the number of decimal points
	 *
	 * @return string
	 * @since 6.3
	 * @see number_format()
	 */
	public static function numberFormat(float $number, int $decimals = 0): string {
		$decimal_separator = substr(elgg_echo('number_counter:decimal_separator'), 0, 1);
		$thousands_separator = substr(elgg_echo('number_counter:thousands_separator'), 0, 1);
		
		return number_format($number, $decimals, $decimal_separator, $thousands_separator);
	}
}
