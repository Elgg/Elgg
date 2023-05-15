<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s, bitte bestätige Deine Email-Adresse für %s!",
	'email:confirm:success' => "Du hast Deine Email-Adresse bestätigt!",
	'email:confirm:fail' => "Deine Email-Adresse konnte nicht bestätigt werden...",

	'uservalidationbyemail:emailsent' => "Die Email an <em>%s</em> wurde gesendet.",
	'uservalidationbyemail:registerok' => "Um Deinen Account zu aktivieren, mußt Du Deine Email-Adresse bestätigen, indem Du dem Link in der Email folgst, die wir Dir gerade gesendet haben.",

	'uservalidationbyemail:admin:resend_validation' => 'Bestätigungs-Email erneut senden',
	'uservalidationbyemail:confirm_resend_validation' => 'Bestätigungs-Email an %s erneut senden?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Bestätigungs-Email erneut an markierte Benutzeraccounts senden?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Unbekannter Benutzer',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Bestätigungs-Email konnte nicht versendet werden.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Die Bestätigungs-Email konnte nicht an alle markierten Benutzeraccounts versendet werden.',

	'uservalidationbyemail:messages:resent_validation' => 'Bestätigungs-Email gesendet.',
	'uservalidationbyemail:messages:resent_validations' => 'Die Bestätigungs-Email wurde erneut an alle markierten Benutzeraccount gesendet.',
);
