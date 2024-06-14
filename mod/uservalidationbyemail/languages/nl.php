<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s, bevestig alsjeblieft je e-mailadres voor %s!",
	'email:confirm:success' => "Je hebt je e-mailadres bevestigd!",
	'email:confirm:fail' => "Je e-mailadres kon niet worden geverifieerd...",

	'uservalidationbyemail:emailsent' => "E-mail verzonden naar <em>%s</em>",
	'uservalidationbyemail:registerok' => "Wil je jouw account activeren? Bevestig dan je e-mailadres door op de link die we je gestuurd hebben te klikken.",
	'uservalidationbyemail:change_email' => "Validatie e-mail nogmaals verzenden",
	'uservalidationbyemail:change_email:info' => "Je account is nog niet gevalideerd, dus het aanmelden is mislukt. Je kunt een nieuwe validatie link aanvragen of het e-mailadres van je account bijwerken.",

	'uservalidationbyemail:admin:resend_validation' => 'Validatie nogmaals verzenden',
	'uservalidationbyemail:confirm_resend_validation' => 'Validatie e-mail versturen aan %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Validatie-e-mail versturen aan geselecteerde gebruikers?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Onbekende gebruikers',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Kon de validatie-e-mail niet versturen.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Kon de validatie-e-mail niet versturen aan de geselecteerde gebruikers.',

	'uservalidationbyemail:messages:resent_validation' => 'Validatie opnieuw verzonden',
	'uservalidationbyemail:messages:resent_validations' => 'Validatie opnieuw verzonden aan alle geselecteerde gebruikers.',
);
