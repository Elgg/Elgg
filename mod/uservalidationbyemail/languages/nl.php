<?php
return array(
	'email:validate:subject' => "%s, bevestig alsjeblieft je e-mailadres voor %s!",
	'email:validate:body' => "Beste %s,

Voordat je gebruik kunt maken van %s, moet je je e-mail adres bevestigen.

Om je e-mail adres te bevestigen klik op onderstaande link:

%s

Indien je niet op de link kunt klikken, kopieer en plak deze handmatig in je browser.

%s
%s",
	'email:confirm:success' => "Je hebt je e-mailadres bevestigd!",
	'email:confirm:fail' => "Je e-mailadres kon niet worden geverifieerd...",

	'uservalidationbyemail:emailsent' => "E-mail verzonden naar <em>%s</em>",
	'uservalidationbyemail:registerok' => "Wil je jouw account activeren? Bevestig dan je e-mailadres door op de link die we je gestuurd hebben te klikken.",
	'uservalidationbyemail:login:fail' => "Je account is nog niet gevalideerd, dus het aanmelden is mislukt. We hebben je nog een validatiemail verstuurd. Nadat je jouw e-mailadres daarmee hebt bevestigd kun je inloggen!",

	'uservalidationbyemail:admin:resend_validation' => 'Validatie nogmaals verzenden',
	'uservalidationbyemail:confirm_resend_validation' => 'Validatie e-mail versturen aan %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Validatie-e-mail versturen aan geselecteerde gebruikers?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Onbekende gebruikers',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Kon de validatie-e-mail niet versturen.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Kon de validatie-e-mail niet versturen aan de geselecteerde gebruikers.',

	'uservalidationbyemail:messages:resent_validation' => 'Validatie opnieuw verzonden',
	'uservalidationbyemail:messages:resent_validations' => 'Validatie opnieuw verzonden aan alle geselecteerde gebruikers.'
);
