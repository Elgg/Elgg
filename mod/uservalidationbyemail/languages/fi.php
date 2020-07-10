<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s, vahvista sähköpostiosoitteesi palveluun %s.",
	'email:validate:body' => "Hi %s,

Before you can start using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Sähköpostiosoitteesi on vahvistettu.",
	'email:confirm:fail' => "Sähköpostiosoitettasi ei voitu vahvistaa...",

	'uservalidationbyemail:emailsent' => "Osoitteeseen <em>%s</em> on lähetetty vahvistusviesti",
	'uservalidationbyemail:registerok' => "Saat tilin käyttöösi klikkaamalla sähköpostiisi lähetettyä linkkiä. Jos viestiä ei löydy, tarkista myös roskapostikansio.",
	'uservalidationbyemail:login:fail' => "Käyttäjätiliäsi ei ole vahvistettu, joten kirjautuminen epäonnistui. Sähköpostiisi on lähetetty uusi vahvistusviesti.",

	'uservalidationbyemail:admin:resend_validation' => 'Lähetä vahvistusviesti uudelleen',
	'uservalidationbyemail:confirm_resend_validation' => 'Lähetä vahvistusviesti uudelleen käyttäjälle %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Lähetä uusi vahvistusviesti valituille käyttäjille?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Tuntemattomat käyttäjät',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Vahvistusviestin uudelleenlähetys epäonnistui.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Vahvistusviestin uudelleenlähetys ei onnistunut kaikille valituille käyttäjille.',

	'uservalidationbyemail:messages:resent_validation' => 'Vahvistusviesti lähetetty uudelleen.',
	'uservalidationbyemail:messages:resent_validations' => 'Vahvistusviesti uudellenlähetetty valituille käyttäjille.',
	
	'uservalidationbyemail:upgrade:2019090600:title' => 'Track user e-mail validation status',
	'uservalidationbyemail:upgrade:2019090600:description' => 'The e-mail validation status is tracked in a new way. Make sure all pending users are updated to the new tracking in order to still require e-mail validation.',
);
