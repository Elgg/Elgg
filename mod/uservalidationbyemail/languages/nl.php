<?php
return array(
	'admin:users:unvalidated' => 'Ongevalideerd',
	
	'email:validate:subject' => "%s bevestig aub je email adres voor %s!",
	'email:validate:body' => "Beste %s,

Voordat je gebruik kunt maken van %s, moet je je e-mail adres bevestigen.

Om je e-mail adres te bevestigen moet je op onderstaande link klikken:
%s

Als je niet op de link kunt klikken, kopieer en plak deze dan handmatig in de adresbalk van je browser.

%s
%s",
	'email:confirm:success' => "Je hebt je e-mail adres bevestigd!",
	'email:confirm:fail' => "Je e-mail adres kon niet worden geverifieerd...",

	'uservalidationbyemail:emailsent' => "E-mail verzonden naar <em>%s</em>",
	'uservalidationbyemail:registerok' => "Om je account te activeren, bevestig je email adres door te klikken op de link die we je gestuurd hebben.",
	'uservalidationbyemail:login:fail' => "Je account is nog niet gevalideerd dus het aanmelden is mislukt. Er is nog een validatie mail verstuurd.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Geen ongevalideerde gebruikers',

	'uservalidationbyemail:admin:unvalidated' => 'Ongevalideerd',
	'uservalidationbyemail:admin:user_created' => 'Geregistreerd %s',
	'uservalidationbyemail:admin:resend_validation' => 'Validatie verzenden',
	'uservalidationbyemail:admin:validate' => 'Valideren',
	'uservalidationbyemail:confirm_validate_user' => 'Valideer %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Validatie e-mail versturen aan %s?',
	'uservalidationbyemail:confirm_delete' => 'Verwijder %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Valideer geselecteerde gebruikers?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Validatie e-mail versturen aan geselecteerde gebruikers?',
	'uservalidationbyemail:confirm_delete_checked' => 'Verwijder geselecteerde gebruikers?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Onbekende gebruikers',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Kon gebruiker niet valideren.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Kon de geselecteerde gebruikers niet valideren.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Kon de gebruiker niet verwijderen.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Kon de geselecteerde gebruikers niet verwijderen.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Kon de validatie e-mail niet versturen.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Kon de validatie e-mail niet versturen aan de geselecteerde gebruikers.',

	'uservalidationbyemail:messages:validated_user' => 'Gebruiker gevalideerd.',
	'uservalidationbyemail:messages:validated_users' => 'Alle geselecteerde gebruikers gevalideerd.',
	'uservalidationbyemail:messages:deleted_user' => 'Gebruiker verwijderd.',
	'uservalidationbyemail:messages:deleted_users' => 'Alle geselecteerde gebruikers verwijderd.',
	'uservalidationbyemail:messages:resent_validation' => 'Validatie opnieuw verzonden',
	'uservalidationbyemail:messages:resent_validations' => 'Validatie opnieuw verzonden aan alle geselecteerde gebruikers.'

);
