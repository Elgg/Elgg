<?php
namespace Elgg;

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
	 * @param string $name The register. Empty string for all.
	 *
	 * @return array The array of registers dumped
	 */
	function dumpRegister($name = '') {
		$registers = $this->loadRegisters($name);

		if ($name !== "") {
			$return = array();
			$return[$name] = empty($registers[$name]) ? [] : $registers[$name];
			unset($registers[$name]);
		} else {
			$return = $registers;
			$registers = array();
		}

		$this->saveRegisters($registers);
		return $return;
	}

	/**
	 * Counts the number of messages, either globally or in a particular register
	 *
	 * @param string $register_name Optionally, the register
	 *
	 * @return integer The number of messages
	 */
	function count($register_name = "") {
		$registers = $this->loadRegisters($register_name);

		if ($register_name !== '') {
			return empty($registers[$register_name]) ? 0 : count($registers[$register_name]);
		}

		$count = 0;
		foreach ($registers as $register) {
			$count += count($register);
		}
		return $count;
	}

	/**
	 * Display a system message on next page load.
	 *
	 * @see system_messages()
	 *
	 * @param string|array $message Message or messages to add
	 *
	 * @return bool
	 */
	function addSuccessMessage($message) {
		return $this->addMessageToRegister($message, "success");
	}

	/**
	 * Display an error on next page load.
	 *
	 * @see system_messages()
	 *
	 * @param string|array $error Error or errors to add
	 *
	 * @return bool
	 */
	function addErrorMessage($error) {
		return $this->addMessageToRegister($error, "error");
	}

	/**
	 * Add a message(s) to a named register to be displayed
	 *
	 * Messages will not be displayed immediately, but are stored in the queue
	 * for later display, usually upon next page load.
	 *
	 * The method of displaying these messages differs depending upon plugins and
	 * viewtypes.  The core default viewtype retrieves messages in
	 * {@link views/default/page/shells/default.php} and displays messages as
	 * javascript popups.
	 *
	 * @internal Messages are stored as strings in the Elgg session as ['msg'][$register] array.
	 *
	 * @param string|array $message
	 * @param string       $register_name
	 *
	 * @return bool
	 * @access private
	 */
	function addMessageToRegister($message, $register_name = '') {
		$registers = $this->loadRegisters($register_name);

		if (is_string($message)) {
			$message = array($message);
		}
		if (!isset($registers[$register_name])) {
			$registers[$register_name] = [];
		}
		$registers[$register_name] = array_merge($registers[$register_name], $message);

		$this->saveRegisters($registers);
		return true;
	}

	/**
	 * Load the registers from the session
	 *
	 * @param string $accessed_register The register being accessed
	 *
	 * @return array
	 */
	protected function loadRegisters($accessed_register = '') {
		$registers = $this->session->get('msg', array());

		if (!isset($registers[$accessed_register]) && $accessed_register !== '') {
			$registers[$accessed_register] = array();
		}

		return $registers;
	}

	/**
	 * Save the registers to the session
	 *
	 * @param array $registers The message registers
	 * @return void
	 */
	protected function saveRegisters(array $registers) {
		$this->session->set('msg', $registers);
	}
}
