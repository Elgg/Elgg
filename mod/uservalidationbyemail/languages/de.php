<?php
return array(
	'admin:users:unvalidated' => 'Inaktiv',
	
	'email:validate:subject' => "%s, bitte bestätige Deine Email-Adresse!",
	'email:validate:body' => "Hallo %s,

bevor Du Dich auf der Community-Seite %s anmelden kannst, mußt Du Deine Email-Addresse bestätigen.

Um Deine angegebene Email-Addresse zu bestätigen, folgende diesem Link:

%s

Wenn Du nicht direkt auf den Link klicken kannst, kopiere ihn bitte von Hand in die Adresszeile Deines Browsers.

%s
%s
",
	'email:confirm:success' => "Du hast Deine Email-Adresse bestätigt!",
	'email:confirm:fail' => "Deine Email-Adresse konnte nicht bestätigt werden...",

	'uservalidationbyemail:emailsent' => "Die Email an <em>%s</em> wurde gesendet.",
	'uservalidationbyemail:registerok' => "Um Deinen Account zu aktivieren, mußt Du Deine Email-Adresse bestätigen, indem Du dem Link in der Email folgst, die wir Dir gerade gesendet haben.",
	'uservalidationbyemail:login:fail' => "Dein Account ist noch nicht validiert, daher ist die Anmeldung auf dieser Community-Seite noch nicht möglich. Es wurde eine neue Validierungs-Email an die von Dir angegebene Email-Adresse gesendet.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Keine inaktiven Benutzeraccounts.',

	'uservalidationbyemail:admin:unvalidated' => 'Inaktiv',
	'uservalidationbyemail:admin:user_created' => '%s registriert',
	'uservalidationbyemail:admin:resend_validation' => 'Bestätigungs-Email erneut senden',
	'uservalidationbyemail:admin:validate' => 'Bestätigen',
	'uservalidationbyemail:confirm_validate_user' => '%s bestätigen?',
	'uservalidationbyemail:confirm_resend_validation' => 'Bestätigungs-Email an %s erneut senden?',
	'uservalidationbyemail:confirm_delete' => '%s löschen?',
	'uservalidationbyemail:confirm_validate_checked' => 'Markierte Benutzeraccounts bestätigen?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Bestätigungs-Email erneut an markierte Benutzeraccounts senden?',
	'uservalidationbyemail:confirm_delete_checked' => 'Markierte Benutzeraccounts löschen?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Unbekannter Benutzer',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Benutzeraccount konnte nicht bestätigt werden.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Es konnten nicht alle markierten Benutzeraccounts bestätigt werden.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Benutzeraccount konnte nicht gelöscht werden.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Es konnten nicht alle markierten Benutzeraccounts gelöscht werden.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Bestätigungs-Email konnte nicht versendet werden.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Die Bestätigungs-Email konnte nicht an alle markierten Benutzeraccounts versendet werden.',

	'uservalidationbyemail:messages:validated_user' => 'Benutzeraccount bestätigt.',
	'uservalidationbyemail:messages:validated_users' => 'Alle markierten Benutzeraccount bestätigt.',
	'uservalidationbyemail:messages:deleted_user' => 'Benutzeraccount gelöscht.',
	'uservalidationbyemail:messages:deleted_users' => 'Alle markierten Benutzeraccount gelöscht.',
	'uservalidationbyemail:messages:resent_validation' => 'Bestätigungs-Email gesendet.',
	'uservalidationbyemail:messages:resent_validations' => 'Die Bestätigungs-Email wurde erneut an alle markierten Benutzeraccount gesendet.'

);
