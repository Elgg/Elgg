<?php

use Elgg\WebServices\Di\RestApiErrorHandler;

/**
 * GenericResult Result superclass.
 */
abstract class GenericResult {
	
	/**
	 * The status of the result
	 */
	protected int $status_code;

	/**
	 * Message returned along with the status which is almost always an error message.
	 * This must be human-readable, understandable and localised
	 */
	protected string $message;

	/**
	 * Result store.
	 * Attach result specific information here.
	 *
	 * @var mixed Should probably be an object of some sort.
	 */
	protected $result;

	/**
	 * Set a status code and optional message.
	 *
	 * @param int    $status  The status code.
	 * @param string $message The message.
	 *
	 * @return void
	 */
	protected function setStatusCode(int $status, string $message = ''): void {
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
	protected function setResult($result): void {
		$this->result = $result;
	}

	/**
	 * Return the current status code
	 *
	 * @return int
	 */
	protected function getStatusCode(): int {
		return $this->status_code;
	}

	/**
	 * Return the current status message
	 *
	 * @return null|string
	 */
	protected function getStatusMessage(): ?string {
		return $this->message;
	}

	/**
	 * Return the current result
	 *
	 * @return mixed
	 */
	protected function getResult() {
		return $this->result;
	}

	/**
	 * Serialise to a standard class.
	 *
	 * DEVNOTE: The API is only interested in data, we can not easily serialise
	 * custom classes without the need for 1) the other side being PHP, 2) you need to have the class
	 * definition installed, 3) it's the right version!
	 *
	 * Therefore, I'm not bothering.
	 *
	 * Override this to include any more specific information, however api results
	 * should be attached to the class using setResult().
	 *
	 * if ELGG_DEBUG is set then additional information about the runtime environment and
	 * authentication will be returned.
	 *
	 * @return \stdClass Object containing the serialised result.
	 */
	public function export(): \stdClass {
		$result = new \stdClass;

		$result->status = $this->getStatusCode();
		if ($this->getStatusMessage() != '') {
			$result->message = $this->getStatusMessage();
		}

		$resultdata = $this->getResult();
		if (isset($resultdata)) {
			$result->result = $resultdata;
		}

		if (elgg_get_config('debug')) {
			$errors = RestApiErrorHandler::instance()->getErrors();
			if (!empty($errors)) {
				$result->runtime_errors = $errors;
			}
		}

		return $result;
	}
}
