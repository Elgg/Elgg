<?php
namespace Elgg\SystemMessages;

/**
 * Represents the state of system messages and errors. In elgg_modify_system_messages() this
 * is passed to your given function and must be returned.
 *
 * @see   elgg_modify_system_messages
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
