<?php
/**
* Projects contact language file
*/

$language = array(

/**
* Menu items and titles
*/

'projects_contact:from' => "From:",
'projects_contact:to' => "To:",
'projects_contact:subject' => "Subject:",
'projects_contact:message' => "Message:",
'projects_contact:date' => "Date:",
'projects_contact:projects_contact' => "Projects",
'projects_contact:add' => "Contact with them",
'projects_contact:toggle' => "Toggle All",
'projects_contact:markread' => "Mark as read",
'projects_contact:read' => "Read message",
'projects_contact:owner' => "%s's messages",
'projects_contact:none' => 'No messages found.',
'projects_contact:projects' => 'Inbox',
'projects_contact:inbox' => 'Inbox',
'projects_contact:deleted_sender' => 'This user has been deleted.',
'projects_contact:nomessages' => 'There is no messages.',


/**
* Interaction
*/

'projects_contact:delete:confirm' => "Delete this message?",
'projects_contact:numbertodisplay' => 'Messages:',
'projects_contact:project' => 'Messages',
'projects_contact:noproject' => 'No messages.',
'river:create:object:projects_contact' => '%s new message %s',
'item:object:projects_contact' => 'Contact messages',

/**
* Success messages
*/

'projects_contact:save:success' => "Your message was successfully sended.",
'projects_contact:delete:success' => "This message was deleted.",

/**
* Error messages
*/

'projects_contact:save:failed' => "Your message could not be send. Make sure you've entered data and then try again.",
'projects_contact:save:invalid' => "The message is not valid.",
'projects_contact:delete:failed' => "This message could not be deleted. Please try again.",
'projects_contact:unknown_projects_contact' => 'Cannot find specified projects_contact',
);

add_translation(basename(__FILE__, '.php'), $language);
