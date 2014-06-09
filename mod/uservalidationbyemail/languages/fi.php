<?php
return array(
	'admin:users:unvalidated' => 'Vahvistamattomat käyttäjät',
	
	'email:validate:subject' => "%s, vahvista sähköpostiosoitteesi palveluun %s.",
	'email:validate:body' => "%s,

Ennen kuin voit käyttää palvelua %s, sinun pitää vahvistaa sähköpostiosoitteesi.

Vahvista osoite klikkaamalla alla olevaa linkkiä:

%s

Jos et voi klikata linkkiä, kopioi se selaimesi osoiteriville.

%s
%s
",
	'email:confirm:success' => "Sähköpostiosoitteesi on vahvistettu.",
	'email:confirm:fail' => "Sähköpostiosoitettasi ei voitu vahvistaa...",

	'uservalidationbyemail:emailsent' => "Osoitteeseen <em>%s</em> on lähetetty vahvistusviesti",
	'uservalidationbyemail:registerok' => "Saat tilin käyttöösi klikkaamalla sähköpostiisi lähetettyä linkkiä. Jos viestiä ei löydy, tarkista myös roskapostikansio.",
	'uservalidationbyemail:login:fail' => "Käyttäjätiliäsi ei ole vahvistettu, joten kirjautuminen epäonnistui. Sähköpostiisi on lähetetty uusi vahvistusviesti.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Ei vahvistamattomia käyttäjiä.',

	'uservalidationbyemail:admin:unvalidated' => 'Vahvistamattomat käyttäjät',
	'uservalidationbyemail:admin:user_created' => 'Rekisteröitynyt %s',
	'uservalidationbyemail:admin:resend_validation' => 'Lähetä vahvistusviesti uudelleen',
	'uservalidationbyemail:admin:validate' => 'Vahvista',
	'uservalidationbyemail:confirm_validate_user' => 'Vahvista %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Lähetä vahvistusviesti uudelleen käyttäjälle %s?',
	'uservalidationbyemail:confirm_delete' => 'Poista %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Vahvista valitut käyttäjät?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Lähetä uusi vahvistusviesti valituille käyttäjille?',
	'uservalidationbyemail:confirm_delete_checked' => 'Poista valitut käyttäjät?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Tuntemattomat käyttäjät',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Käyttäjää ei voitu vahvistaa.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Kaikkia valittuja käyttäjiä ei voitu vahvistaa.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Käyttäjää ei voitu poistaa.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Kaikkia valittuja käyttäjiä ei voitu poistaa.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Vahvistusviestin uudelleenlähetys epäonnistui.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Vahvistusviestin uudelleenlähetys ei onnistunut kaikille valituille käyttäjille.',

	'uservalidationbyemail:messages:validated_user' => 'Käyttäjä vahvistettu',
	'uservalidationbyemail:messages:validated_users' => 'Kaikki valitut käyttäjät vahvistettu.',
	'uservalidationbyemail:messages:deleted_user' => 'Käyttäjä poistettu.',
	'uservalidationbyemail:messages:deleted_users' => 'Kaikki valitut käyttäjät poistettu.',
	'uservalidationbyemail:messages:resent_validation' => 'Vahvistusviesti lähetetty uudelleen.',
	'uservalidationbyemail:messages:resent_validations' => 'Vahvistusviesti uudellenlähetetty valituille käyttäjille.'

);
