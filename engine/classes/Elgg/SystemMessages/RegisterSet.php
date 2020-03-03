<?php
namespace Elgg\SystemMessages;

/**
 * Represents the state of system messages and errors.
 *
 * This is returned by elgg()->system_messages->loadRegisters() and must be given to elgg()->system_messages->saveRegisters().
 *
 * @see   elgg()->system_messages->loadRegisters()
 * @see   elgg()->system_messages->saveRegisters($set)
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
