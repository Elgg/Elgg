<?php
namespace Elgg;

use Elgg\SystemMessages\RegisterSet;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage UX
 * @since      1.11.0
 */
class SystemMessagesService {

	const SUCCESS = 'success';
	const ERROR = 'error';
	const SESSION_KEY = 'msg';

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * Constructor
	 *
	 * @param \ElggSession $session The Elgg session
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
	 */
	public function dumpRegister($register_name = '') {
		$set = $this->loadSet();
		$return = [];

		foreach ([self::SUCCESS, self::ERROR] as $key) {
			if ($register_name === '' || $register_name === $key) {
				if ($set->{$key} || $register_name === $key) {
					$return[$key] = $set->{$key};
				}

				$set->{$key} = [];
			}
		}

		$this->saveSet($set);
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
		$set = $this->loadSet();
		$count = 0;

		foreach ([self::SUCCESS, self::ERROR] as $key) {
			if ($register_name === $key || $register_name === '') {
				$count += count($set->{$key});
			}
		}

		return $count;
	}

	/**
	 * Display a system message on next page load.
	 *
	 * @see system_messages()
	 *
	 * @param string|string[] $message Message or messages to add
	 *
	 * @return void
	 */
	public function addSuccessMessage($message) {
		$this->modifyRegisters(function (RegisterSet $set) use ($message) {
			foreach ((array)$message as $str) {
				$set->success[] = $str;
			}
			return $set;
		});
	}

	/**
	 * Display an error on next page load.
	 *
	 * @see system_messages()
	 *
	 * @param string|string[] $error Error or errors to add
	 *
	 * @return void
	 */
	public function addErrorMessage($error) {
		$this->modifyRegisters(function (RegisterSet $set) use ($error) {
			foreach ((array)$error as $str) {
				$set->error[] = $str;
			}
			return $set;
		});
	}

	/**
	 * Modify the system messages and errors, by giving a function that modifies and returns a RegisterSet.
	 *
	 * @param callable $func Function that accepts and returns an instance of Elgg\SystemMessages\RegisterSet.
	 *
	 * @return void
	 */
	public function modifyRegisters(callable $func) {
		$set = $func($this->loadSet());
		if (!$set instanceof RegisterSet) {
			throw new \RuntimeException('Given function $func must return a ' . RegisterSet::class);
		}
		$this->saveSet($set);
	}

	/**
	 * Load the registers from the session
	 *
	 * @return RegisterSet
	 */
	protected function loadSet() {
		$registers = $this->session->get(self::SESSION_KEY, array());
		$set = new RegisterSet();
		$set->success = isset($registers[self::SUCCESS]) ? $registers[self::SUCCESS] : [];
		$set->error = isset($registers[self::ERROR]) ? $registers[self::ERROR] : [];
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
	 * @internal Messages are stored as strings in the Elgg session as ['msg'][$register] array.
	 *
	 * @param RegisterSet $set The set of registers
	 * @return void
	 */
	protected function saveSet(RegisterSet $set) {
		$filter = function ($el) {
			return is_string($el) && $el !== "";
		};

		$data = [];
		foreach ([self::SUCCESS, self::ERROR] as $key) {
			$arr = array_filter($set->{$key}, $filter);
			if ($arr) {
				$data[$key] = array_values($arr);
			}
		}

		$this->session->set(self::SESSION_KEY, $data);
	}
}
