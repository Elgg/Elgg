<?php

/**
 * Convert a database row to a new \ElggRelationship
 *
 * @param \stdClass $row Database row from the relationship table
 *
 * @return \ElggRelationship|false
 * @access private
 */
function row_to_elggrelationship($row) {
	elgg_deprecated_notice(__FUNCTION__ . " is deprecated.", 2.1);
	return _elgg_services()->relationshipsTable->rowToElggRelationship($row);
}

/**
 * Queues a message to be displayed.
 *
 * Messages will not be displayed immediately, but are stored in
 * for later display, usually upon next page load.
 *
 * The method of displaying these messages differs depending upon plugins and
 * viewtypes.  The core default viewtype retrieves messages in
 * {@link views/default/page/shells/default.php} and displays messages as
 * javascript popups.
 *
 * @note Internal: Messages are stored as strings in the Elgg session as ['msg'][$register] array.
 *
 * @warning This function is used to both add to and clear the message
 * stack.  If $messages is null, $register will be returned and cleared.
 * If $messages is null and $register is empty, all messages will be
 * returned and removed.
 *
 * @param mixed  $message  Optionally, a single message or array of messages to add, (default: null)
 * @param string $register Types of message: "error", "success" (default: success)
 * @param bool   $count    Count the number of messages (default: false)
 *
 * @return bool|array Either the array of messages, or a response regarding
 *                          whether the message addition was successful.
 *
 * @deprecated
 */
function system_messages($message = null, $register = "success", $count = false) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', '2.1');

	$svc = _elgg_services()->systemMessages;
	if ($count) {
		return $svc->count($register);
	}
	if ($message === null) {
		return $svc->dumpRegister($register);
	}
	if (!$register) {
		return false;
	}
	$set = $svc->loadRegisters();
	if (!isset($set->{$register})) {
		$set->{$register} = [];
	}
	foreach ((array)$message as $str) {
		$set->{$register}[] = $str;
	}
	$svc->saveRegisters($set);
	return true;
}
