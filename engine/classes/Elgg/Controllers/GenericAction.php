<?php

namespace Elgg\Controllers;

use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * Generic action controller
 *
 * @since 6.2
 */
abstract class GenericAction {
	
	protected \Elgg\Request $request;
	
	/**
	 * Invoke the action steps
	 *
	 * @param \Elgg\Request $request the request for the action
	 *
	 * @return \Elgg\Http\Response
	 */
	final public function __invoke(\Elgg\Request $request): \Elgg\Http\Response {
		$this->request = $request;
		
		try {
			$this->sanitize();
			$this->validate();
			$this->executeBefore();
			$this->execute();
			$this->executeAfter();
			
			return $this->success();
		} catch (\Elgg\Exceptions\HttpException $e) {
			return $this->error($e->getMessage());
		}
	}
	
	/**
	 * Sanitizes input for the action
	 *
	 * @return void
	 */
	protected function sanitize(): void {
	}
	
	/**
	 * Validates the action
	 *
	 * @return void
	 */
	protected function validate(): void {
	}
	
	/**
	 * Preparation before executing the action
	 *
	 * @return void
	 */
	protected function executeBefore(): void {
	}
	
	/**
	 * Main part of the action
	 *
	 * @return void
	 */
	protected function execute(): void {
	}
	
	/**
	 * Action part after the main execution
	 *
	 * @return void
	 */
	protected function executeAfter(): void {
	}
	
	/**
	 * Will be used when an action is successfully finished
	 *
	 * @return OkResponse
	 */
	protected function success(): OkResponse {
		return elgg_ok_response();
	}
	
	/**
	 * Will be used when something wrong happened during the handling of the action
	 *
	 * @param string $message Message to show to the user
	 *
	 * @return ErrorResponse
	 */
	protected function error(string $message): ErrorResponse {
		return elgg_error_response($message);
	}
}
