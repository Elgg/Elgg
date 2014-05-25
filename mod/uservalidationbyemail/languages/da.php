<?php
return array(
	'admin:users:unvalidated' => 'Ikke valideret',
	
	'email:validate:subject' => "%s vær venlig at bekræfte din e-mail adresse for %s!",
	'email:validate:body' => "Hej %s,

Før du kan begynde at bruge %s, skal du bekræfte din e-mail adresse.

Vær venlig at bekræfte din e-mail adresse ved at klikke på linket herunder:

%s

Hvis du ikke kan klikke på linket så kopier og indsæt det i din browser manuelt.

%s
%s
",
	'email:confirm:success' => "Du har bekræftet din e-mail adresse!",
	'email:confirm:fail' => "Din e-mail adresse kunne ikke bekræftes...",

	'uservalidationbyemail:emailsent' => "Email sendt til <em>%s</em>",
	'uservalidationbyemail:registerok' => "Aktiver venligst din konto ved at bekræfte din e-mail adresse i det link vi lige har sendt til dig.",
	'uservalidationbyemail:login:fail' => "Din konto er ikke valideret så log ind forsøget mislykkedes. En ny validerings e-mail er blevet sendt.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Ingen ikke validerede brugere.',

	'uservalidationbyemail:admin:unvalidated' => 'Ikke valideret',
	'uservalidationbyemail:admin:user_created' => 'Registrerede %s',
	'uservalidationbyemail:admin:resend_validation' => 'Gensend validering',
	'uservalidationbyemail:admin:validate' => 'Valider',
	'uservalidationbyemail:confirm_validate_user' => 'Valider %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Gensend validerings e-mail til %s?',
	'uservalidationbyemail:confirm_delete' => 'Slet %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Valider markerede brugere?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Gensend validering til markerede brugere?',
	'uservalidationbyemail:confirm_delete_checked' => 'Slet markerede brugere?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Ukendte brugere',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Kunne ikke validate bruger.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Kunne ikke validere alle markerede brugere.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Kunne ikke slette bruger.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Kunne ikke slette alle markerede brugere.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Kunne ikke gensende anmodning om validering.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Kunne ikke gensende alle anmodninger om validering til markerede brugere.',

	'uservalidationbyemail:messages:validated_user' => 'Bruger valideret.',
	'uservalidationbyemail:messages:validated_users' => 'Alle markerede brugere validerede.',
	'uservalidationbyemail:messages:deleted_user' => 'Bruger slettet.',
	'uservalidationbyemail:messages:deleted_users' => 'Alle markerede brugere slettet.',
	'uservalidationbyemail:messages:resent_validation' => 'Anmodning om validering gensendt.',
	'uservalidationbyemail:messages:resent_validations' => 'Anmodning om validering gensendt til alle markerede brugere.'

);
