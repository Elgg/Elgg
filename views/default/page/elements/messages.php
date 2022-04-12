<?php
/**
 * Elgg global system message list
 * Lists all system messages
 *
 * @uses $vars['object'] The array of message registers
 */

$messages = (array) elgg_extract('object', $vars, []);

if (!empty($messages)) {
	elgg_require_js('elgg/system_messages');
}

// hidden li so we validate, we need this for javascript added system messages
$list_items = elgg_format_element('li', ['class' => 'hidden']);

foreach ($messages as $type => $list) {
	/** @var \ElggSystemMessage $message **/
	foreach ($list as $message) {
		$message_vars = $message->getVars(['title' => false]);
		if ($message->getTtl() > -1) {
			// 0 means persistent
			$message_vars['data-ttl'] = $message->getTtl() * 1000;
		} elseif (isset($message_vars['link'])) {
			// keep the message persistent if there is a link
			$message_vars['data-ttl'] = 0;
		}
		
		$list_items .= elgg_format_element('li', [], elgg_view_message($message->getType(), $message->getMessage(), $message_vars));
	}
}

echo elgg_format_element('ul', ['class' => 'elgg-system-messages'], $list_items);
