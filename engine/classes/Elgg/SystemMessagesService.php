<?php
namespace Elgg;

use Elgg\SystemMessages\RegisterSet;

/**
 * System messages service
 *
 * Use elgg()->system_messages
 *
 * @since      1.11.0
 */
class SystemMessagesService {

	const SUCCESS = 'success';
	const ERROR = 'error';
	const SESSION_KEY = '_elgg_msgs';

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * Constructor
	 *
	 * @param \ElggSession $session The Elgg session
	 * @access private
	 * @internal
	 */
	public function __construct(\ElggSession $session) {
		$this->session = $session;
	}

	/**
	 * Empty and return the given register or all registers. In each case, the return value is
	 * a filtered version of the full registers array.
	 *
	 * @param string $register_name The register. Empty string for all.
	 *
	 * @return array The array of registers dumped
	 * @access private
	 * @internal
	 */
	public function dumpRegister($register_name = '') {
		$set = $this->loadRegisters();
		$return = [];

		foreach ($set as $prop => $values) {
			if ($register_name === $prop || $register_name === '') {
				if ($values || $register_name === $prop) {
					$return[$prop] = $values;
				}

				$set->{$prop} = [];
			}
		}

		// support arbitrary registers for 2.0 BC
		if ($register_name && !isset($return[$register_name])) {
			$return[$register_name] = [];
		}

		$this->saveRegisters($set);
		return $return;
	}

	/**
	 * Counts the number of messages, either globally or in a particular register
	 *
	 * @param string $register_name Optionally, the register
	 *
	 * @return integer The number of messages
	 */
	public function count($register_name = "") {
		$set = $this->loadRegisters();
		$count = 0;

		foreach ($set as $prop => $values) {
			if ($register_name === $prop || $register_name === '') {
				$count += count($values);
			}
		}

		return $count;
	}

	/**
	 * Display a system message on next page load.
	 *
	 * @param string|string[] $message Message or messages to add
	 *
	 * @return void
	 */
	public function addSuccessMessage($message) {
		$set = $this->loadRegisters();
		foreach ((array) $message as $str) {
			$set->success[] = $str;
		}
		$this->saveRegisters($set);
	}

	/**
	 * Display an error on next page load.
	 *
	 * @param string|string[] $error Error or errors to add
	 *
	 * @return void
	 */
	public function addErrorMessage($error) {
		$set = $this->loadRegisters();
		foreach ((array) $error as $str) {
			$set->error[] = $str;
		}
		$this->saveRegisters($set);
	}

	/**
	 * Load the registers from the session
	 *
	 * @return RegisterSet
	 */
	public function loadRegisters() {
		$registers = $this->session->get(self::SESSION_KEY, []);
		$set = new RegisterSet();
		foreach ($registers as $key => $register) {
			$set->{$key} = $register;
		}
		return $set;
	}

	/**
	 * Save the registers to the session
	 *
	 * The method of displaying these messages differs depending upon plugins and
	 * viewtypes.  The core default viewtype retrieves messages in
	 * {@link views/default/page/shells/default.php} and displays messages as
	 * javascript popups.
	 *
	 * Messages are stored as strings in the Elgg session as ['msg'][$register] array.
	 *
	 * @param RegisterSet $set The set of registers
	 * @return void
	 */
	public function saveRegisters(RegisterSet $set) {
		$filter = function ($el) {
			return is_string($el) && $el !== "";
		};

		$data = [];
		foreach ($set as $prop => $values) {
			if (!is_array($values)) {
				continue;
			}
			$arr = array_filter($values, $filter);
			if ($arr) {
				$data[$prop] = array_values($arr);
			}
		}

		$this->session->set(self::SESSION_KEY, $data);
	}
}
