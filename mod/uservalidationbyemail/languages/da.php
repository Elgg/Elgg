<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s vær venlig at bekræfte din e-mail adresse for %s!",
	'email:validate:body' => "Hi %s,

Before you can start using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Du har bekræftet din e-mail adresse!",
	'email:confirm:fail' => "Din e-mail adresse kunne ikke bekræftes...",

	'uservalidationbyemail:emailsent' => "Email sendt til <em>%s</em>",
	'uservalidationbyemail:registerok' => "Aktiver venligst din konto ved at bekræfte din e-mail adresse i det link vi lige har sendt til dig.",
	'uservalidationbyemail:login:fail' => "Din konto er ikke valideret så log ind forsøget mislykkedes. En ny validerings e-mail er blevet sendt.",

	'uservalidationbyemail:admin:resend_validation' => 'Gensend validering',
	'uservalidationbyemail:confirm_resend_validation' => 'Gensend validerings e-mail til %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Gensend validering til markerede brugere?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Ukendte brugere',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Kunne ikke gensende anmodning om validering.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Kunne ikke gensende alle anmodninger om validering til markerede brugere.',

	'uservalidationbyemail:messages:resent_validation' => 'Anmodning om validering gensendt.',
	'uservalidationbyemail:messages:resent_validations' => 'Anmodning om validering gensendt til alle markerede brugere.',
	
	'uservalidationbyemail:upgrade:2019090600:title' => 'Track user e-mail validation status',
	'uservalidationbyemail:upgrade:2019090600:description' => 'The e-mail validation status is tracked in a new way. Make sure all pending users are updated to the new tracking in order to still require e-mail validation.',
);
