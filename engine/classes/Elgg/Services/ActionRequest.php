<?php
namespace Elgg\Services;

/**
 * Models the API handed to an action handler
 */
interface ActionRequest {

	/**
	 * Get the name of the action
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
