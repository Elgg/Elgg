<?php
/**
 * GenericResult Result superclass.
 *
 * @package    Elgg.Core
 * @subpackage WebServicesAPI
 */
abstract class GenericResult {
	/**
	 * The status of the result.
	 * @var int
	 */
	private $status_code;

	/**
	 * Message returned along with the status which is almost always an error message.
	 * This must be human readable, understandable and localised.
	 * @var string
	 */
	private $message;

	/**
	 * Result store.
	 * Attach result specific informaton here.
	 *
	 * @var mixed. Should probably be an object of some sort.
	 */
	private $result;

	/**
	 * Set a status code and optional message.
	 *
	 * @param int    $status  The status code.
	 * @param string $message The message.
	 *
	 * @return void
	 */
	protected function setStatusCode($status, $message = "") {
		$this->status_code = $status;
		$this->message = $message;
	}

	/**
	 * Set the result.
	 *
	 * @param mixed $result The result
	 *
	 * @return void
	 */
	protected function setResult($result) {
		$this->result = $result;
	}

	/**
	 * Return the current status code
	 *
	 * @return string
	 */
	protected function getStatusCode() {
		return $this->status_code;
	}

	/**
	 * Return the current status message
	 *
	 * @return string
	 */
	protected function getStatusMessage() {
		return $this->message;
	}

	/**
	 * Return the current result
	 *
	 * @return string
	 */
	protected function getResult() {
		return $this->result;
	}

	/**
	 * Serialise to a standard class.
	 *
	 * DEVNOTE: The API is only interested in data, we can not easily serialise
	 * custom classes without the need for 1) the other side being PHP, 2) you need to have the class
	 * definition installed, 3) its the right version!
	 *
	 * Therefore, I'm not bothering.
	 *
	 * Override this to include any more specific information, however api results
	 * should be attached to the class using setResult().
	 *
	 * if $CONFIG->debug is set then additional information about the runtime environment and
	 * authentication will be returned.
	 *
	 * @return stdClass Object containing the serialised result.
	 */
	public function export() {
		global $ERRORS, $CONFIG, $_PAM_HANDLERS_MSG;

		$result = new stdClass;

		$result->status = $this->getStatusCode();
		if ($this->getStatusMessage() != "") {
			$result->message = $this->getStatusMessage();
		}

		$resultdata = $this->getResult();
		if (isset($resultdata)) {
			$result->result = $resultdata;
		}

		if (isset($CONFIG->debug)) {
			if (count($ERRORS)) {
				$result->runtime_errors = $ERRORS;
			}

			if (count($_PAM_HANDLERS_MSG)) {
				$result->pam = $_PAM_HANDLERS_MSG;
			}
		}

		return $result;
	}
}
