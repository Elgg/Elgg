<?php

namespace Elgg\Controllers;

use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * Generic action controller
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
			$this->validateInput();
			$this->validatePermissions();
			$this->prepare();
			$this->execute();
			$this->post();
			
			return $this->success();
		} catch (\Throwable $t) {
			return $this->error($t->getMessage());
		}
	}
	
	/**
	 * Sanitizes input for the action
	 *
	 * @return void
	 */
	public function sanitize(): void {
	}
	
	/**
	 * Validates input for the action
	 *
	 * @return void
	 */
	public function validateInput(): void {
	}
	
	/**
	 * Validates permissions for the action
	 *
	 * @return void
	 */
	public function validatePermissions(): void {
	}
	
	/**
	 * Sanitizes input for the action
	 *
	 * @return void
	 */
	public function prepare(): void {
	}
	
	/**
	 * Main part of the action
	 *
	 * @return void
	 */
	public function execute(): void {
	}
	
	/**
	 * Action part after the main execute
	 *
	 * @return void
	 */
	public function post(): void {
	}
	
	/**
	 * Will be used when an action is successfully finished
	 *
	 * @return OkResponse
	 */
	public function success(): OkResponse {
		return elgg_ok_response();
	}
	
	/**
	 * Will be used when something wrong happened during the handling of the action
	 *
	 * @param string $message Message to show to the user
	 *
	 * @return ErrorResponse
	 */
	public function error(string $message): ErrorResponse {
		return elgg_error_response($message);
	}
}
