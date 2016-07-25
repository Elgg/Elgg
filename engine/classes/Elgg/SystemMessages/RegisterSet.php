<?php
namespace Elgg\SystemMessages;

/**
 * Represents the state of system messages and errors.
 *
 * This is returned by elgg_get_system_messages() and must be given to elgg_set_system_messages().
 *
 * @see   elgg_get_system_messages
 * @see   elgg_set_system_messages
 * @since 2.1
 */
class RegisterSet {

	/**
	 * @var string[] Strings added via system_message()
	 *
	 * @note do not change this property name. It must match SystemMessagesService::SUCCESS
	 */
	public $success = [];

	/**
	 * @var string[] Strings added via register_error()
	 *
	 * @note do not change this property name. It must match SystemMessagesService::ERROR
	 */
	public $error = [];
}
